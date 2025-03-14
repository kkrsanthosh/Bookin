<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalController extends Controller
{
    protected $apiContext;

    public function __construct()
    {
        // Fetch PayPal configuration from database
        $paypalConfiguration = Configuration::get();

        // Set up PayPal environment
        $clientId = $paypalConfiguration[4]->config_value;
        $clientSecret = $paypalConfiguration[5]->config_value;
        $mode = $paypalConfiguration[3]->config_value;

        if ($mode == "sandbox") {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $this->apiContext = new PayPalHttpClient($environment);
    }

    public function paywithpaypal(Request $request, $planId)
    {

        if (Auth::check()) {
            $planDetails = Plan::where('plan_id', $planId)->where('status', 1)->first();
            $config = Configuration::get();
            $userData = Auth::user();
            $billing_details = json_decode($userData->billing_details, true);

            if ($planDetails == null) {
                return back();
            } else {
                // Paid amount
                $plan_features = is_string($planDetails->plan_features)
                    ? json_decode($planDetails->plan_features, true)
                    : $planDetails->plan_features;


                $payment_gateway_charge = round((float)($planDetails->plan_price) * ($plan_features['payment_gateway_charge'] / 100), 2);

                // Calculate total               

                $amountToBePaid = ((float)($planDetails->plan_price) * ((float)($config[25]->config_value) / 100)) + (float)($planDetails->plan_price) + (float)($payment_gateway_charge);
                $amountToBePaidPaise = round($amountToBePaid, 2);

                // Construct PayPal order request
                $request = new OrdersCreateRequest();
                $request->prefer('return=representation');
                $request->body = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => $config[1]->config_value,
                            'value' => $amountToBePaidPaise,
                        ]
                    ]],
                    'application_context' => [
                        'cancel_url' => route('paypalPaymentStatus'),
                        'return_url' => route('paypalPaymentStatus'),
                    ]
                ];


                try {
                    // Create PayPal order
                    $response = $this->apiContext->execute($request);
                    foreach ($response->result->links as $link) {
                        if ($link->rel == 'approve') {
                            $redirectUrl = $link->href;
                            break;
                        }
                    }

                    // Generate JSON
                    $invoice_details = [];

                    $invoice_details['from_billing_name'] = $config[16]->config_value;
                    $invoice_details['from_billing_address'] = $config[19]->config_value;
                    $invoice_details['from_billing_city'] = $config[20]->config_value;
                    $invoice_details['from_billing_state'] = $config[21]->config_value;
                    $invoice_details['from_billing_zipcode'] = $config[22]->config_value;
                    $invoice_details['from_billing_country'] = $config[23]->config_value;
                    $invoice_details['from_vat_number'] = $config[26]->config_value;
                    $invoice_details['from_billing_phone'] = $config[18]->config_value;
                    $invoice_details['from_billing_email'] = $config[17]->config_value;
                    $invoice_details['to_billing_name'] = $billing_details['billing_name'];
                    $invoice_details['to_billing_address'] = $billing_details['billing_address'];
                    $invoice_details['to_billing_city'] = $billing_details['billing_city'];
                    $invoice_details['to_billing_state'] = $billing_details['billing_state'];
                    $invoice_details['to_billing_zipcode'] = $billing_details['billing_zipcode'];
                    $invoice_details['to_billing_country'] = $billing_details['billing_country'];
                    $invoice_details['to_billing_phone'] = $billing_details['billing_phone'];
                    $invoice_details['to_billing_email'] = $billing_details['billing_email'];
                    $invoice_details['to_vat_number'] = $billing_details['vat_number'];
                    $invoice_details['tax_name'] = $config[24]->config_value;
                    $invoice_details['tax_type'] = $config[14]->config_value;
                    $invoice_details['tax_value'] = (float)($config[25]->config_value);
                    $invoice_details['subtotal'] = $planDetails->plan_price + $payment_gateway_charge;
                    $invoice_details['tax_amount'] = round((float)($planDetails->plan_price) * (float)($config[25]->config_value) / 100, 2);
                    $invoice_details['payment_gateway_charge'] = (float)($payment_gateway_charge);
                    $invoice_details['invoice_amount'] = $amountToBePaidPaise;

                    // Store transaction details in database before redirecting to PayPal
                    $transaction = new Transaction();
                    $transaction->transaction_id = $response->result->id;
                    $transaction->transaction_date = now();
                    $transaction->user_id = Auth::user()->user_id;
                    $transaction->plan_id = $planDetails->plan_id;
                    $transaction->description = $planDetails->plan_name . " Plan";
                    $transaction->payment_gateway_name = "Paypal";
                    $transaction->transaction_total = $amountToBePaidPaise;
                    $transaction->transaction_currency = $config[1]->config_value;
                    $transaction->invoice_details = json_encode($invoice_details);
                    $transaction->transaction_status = "pending";
                    $transaction->save();

                    // Redirect to PayPal for payment
                    return Redirect::away($redirectUrl);
                } catch (\Exception $ex) {

                    if (config('app.debug')) {
                        Session::put('error', 'Connection timeout');
                        return redirect()->route('business.plans.index')->with('failed', trans('Payment failed, Something went wrong!'));
                    } else {
                        Session::put('error', 'Some error occur, sorry for the inconvenience');
                        return redirect()->route('business.plans.index')->with('failed', trans('Payment failed, Something went wrong!'));
                    }
                    return redirect()->route('business.plans.index')->with('failed', trans('Payment failed, Something went wrong!'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function paypalPaymentStatus(Request $request)
    {
        if (empty($request->PayerID) || empty($request->token)) {
            Session::put('error', 'Payment cancelled!');
            return redirect()->route('business.plans.index');
        }

        try {
            // Get the payment ID from the request
            $paymentId = $request->token;
            $orderId = $paymentId;

            // transaction details
            $transactionDetails = Transaction::where('transaction_id', $paymentId)->where('status', 1)->first();

            // User Details
            $user_details = Auth::user();
            $user_plan_details = json_decode($user_details->plan_details, true);

            // Config
            $config = Configuration::get();

            // request
            $request = new OrdersCaptureRequest($paymentId);
            $response = $this->apiContext->execute($request);

            if ($response->statusCode == 201) {

                // Plan data
                $plan_data = Plan::where('plan_id', $transactionDetails->plan_id)->first();
                $term_days = $plan_data->plan_validity;

                // Check plan validity
                if ($user_plan_details['plan_validity'] == "") {

                    // Add days
                    $plan_validity = Carbon::now();
                    $plan_validity->addDays($term_days);

                    // Convert plan details to JSON
                    $planDetails = [
                        'plan_id' => $plan_data->plan_id,
                        'plan_name' => $plan_data->plan_name,
                        'plan_description' => $plan_data->plan_description,
                        'plan_features' => $plan_data->plan_features,
                        'plan_price' => $plan_data->plan_price,
                        'plan_validity' => $plan_data->plan_validity,
                        'is_trial' => $plan_data->is_trial,
                        'is_private' => $plan_data->is_private,
                        'is_recommended' => $plan_data->is_recommended,
                        'is_customer_support' => $plan_data->is_customer_support,
                        'plan_start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                        'plan_end_date' => Carbon::now()->addDays($plan_data->plan_validity)->format('Y-m-d H:i:s'),
                    ];

                    // Update customer details
                    User::where('user_id', Auth::user()->user_id)->update([
                        'plan_details' => json_encode($planDetails)
                    ]);

                    // Transactions count
                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    // Update transaction details
                    Transaction::where('transaction_id', $orderId)->update([
                        'transaction_id' => $paymentId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'transaction_status' => 'completed',
                    ]);

                    // Generate JSON
                    $encode = json_decode($transactionDetails['invoice_details'], true);
                    $details = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'transaction_id' => $paymentId,
                        'to_billing_name' => $encode['to_billing_name'],
                        'to_vat_number' => $encode['to_vat_number'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'plan_price' => $planDetails['plan_price'],
                        'payment_gateway_charge' => $encode['payment_gateway_charge'],
                        'subtotal' => $encode['subtotal'],
                        'tax_amount' => $encode['tax_amount'],
                        'invoice_amount' => $encode['invoice_amount'],
                        'invoice_id' => $config[15]->config_value . $invoice_number,
                        'invoice_date' => $transactionDetails->created_at,
                        'description' => $transactionDetails->description,
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    $details_admin = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'to_billing_name' => $encode['to_billing_name'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'invoice_amount' => $encode['invoice_amount'],
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    // Send email
                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailPlanInvoice($details));
                        Mail::to($encode['from_billing_email'])->send(new \App\Mail\SendEmailPlanAdmin($details_admin));
                    } catch (\Exception $e) {
                        
                    }

                    // Page redirect
                    return redirect()->route('business.plans.index')->with('success', trans('Plan activation success!'));
                } else {

                    // Check plan downgrade 
                    $old_plan_details = $user_plan_details;

                    // Decode the JSON string into an associative array
                    $plan_features = is_string($old_plan_details['plan_features'])
                        ? json_decode($old_plan_details['plan_features'], true)
                        : $old_plan_details['plan_features'];


                    $purchased_plan_features = is_string($plan_data['plan_features'])
                        ? json_decode($plan_data['plan_features'], true)
                        : $plan_data['plan_features'];


                    if (($plan_features['no_of_businesses'] > $purchased_plan_features['no_of_businesses']) || ($plan_features['no_of_services'] > $purchased_plan_features['no_of_services']) || ($plan_features['no_of_employees'] > $purchased_plan_features['no_of_employees'])) {

                        // get business ids
                        $business_ids = Business::where('user_id', Auth::user()->user_id)->where('status', '>=', 0)->pluck('business_id');


                        // deactive businesses
                        Business::where('user_id', Auth::user()->user_id)->update([
                            'status' => 0
                        ]);

                        // deactive services
                        BusinessService::whereIn('business_id', $business_ids)->update([
                            'status' => 0
                        ]);

                        // deactive employees
                        BusinessEmployee::whereIn('business_id', $business_ids)->update([
                            'status' => 0
                        ]);
                    }

                    $message = "";

                    // Check plan id
                    if ($user_plan_details['plan_id'] == $transactionDetails->plan_id) {

                        // Check if plan validity is expired or not.
                        $plan_validity = $user_plan_details['plan_end_date'];
                        $current_date = Carbon::now();
                        $remaining_days = $current_date->diffInDays($plan_validity, false);

                        // Check plan remaining days
                        if ($remaining_days > 0) {
                            // Add days
                            $plan_validity = Carbon::parse($user_plan_details['plan_end_date']);
                            $plan_validity->addDays($term_days);
                            $message = "Plan renewed successfully!";
                        } else {
                            // Add days
                            $plan_validity = Carbon::now();
                            $plan_validity->addDays($term_days);
                            $message = "Plan renewed successfully!";
                        }
                    } else {
                        // Add days
                        $plan_validity = Carbon::now();
                        $plan_validity->addDays($term_days);
                        $message = "Plan activated successfully!";
                    }

                    // Transactions count
                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    // Update transaction details
                    Transaction::where('transaction_id', $orderId)->update([
                        'transaction_id' => $paymentId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'transaction_status' => 'completed',
                    ]);

                    $plan = Plan::where('plan_id', $transactionDetails->plan_id)->firstOrFail();

                    // Convert plan details to JSON
                    $planDetails = [
                        'plan_id' => $plan->plan_id,
                        'plan_name' => $plan->plan_name,  // Assuming plan_details is stored as JSON
                        'plan_description' => $plan->plan_description,
                        'plan_features' => $plan->plan_features,
                        'plan_price' => $plan->plan_price,
                        'plan_validity' => $plan->plan_validity,
                        'is_trial' => $plan->is_trial,
                        'is_private' => $plan->is_private,
                        'is_recommended' => $plan->is_recommended,
                        'is_customer_support' => $plan->is_customer_support,
                        'plan_start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                        'plan_end_date' => $plan_validity->format('Y-m-d H:i:s'),
                    ];

                    // Update customer details
                    User::where('user_id', Auth::user()->user_id)->update([
                        'plan_details' => json_encode($planDetails)
                    ]);

                    // Generate JSON
                    $encode = json_decode($transactionDetails['invoice_details'], true);
                    $details = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'transaction_id' => $paymentId,
                        'to_billing_name' => $encode['to_billing_name'],
                        'to_vat_number' => $encode['to_vat_number'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'plan_price' => $planDetails['plan_price'],
                        'payment_gateway_charge' => $encode['payment_gateway_charge'],
                        'subtotal' => $encode['subtotal'],
                        'tax_amount' => $encode['tax_amount'],
                        'invoice_amount' => $encode['invoice_amount'],
                        'invoice_id' => $config[15]->config_value . $invoice_number,
                        'invoice_date' => $transactionDetails->created_at,
                        'description' => $transactionDetails->description,
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    $details_admin = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'to_billing_name' => $encode['to_billing_name'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'invoice_amount' => $encode['invoice_amount'],
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    // Send email
                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailPlanInvoice($details));
                        Mail::to($encode['from_billing_email'])->send(new \App\Mail\SendEmailPlanAdmin($details_admin));
                    } catch (\Exception $e) {
                    }

                    // Page redirect
                    return redirect()->route('business.plans.index')->with('success', $message);
                }
            } else {
                Transaction::where('transaction_id', $orderId)->update([
                    'transaction_id' => $orderId,
                    'transaction_status' => 'failed',
                ]);

                return redirect()->route('business.plans.index')->with('failed', trans("Payment cancelled!"));
            }
        } catch (HttpException $e) { // Corrected class name
            // Handle the HTTP exception
            // Log the error or display an error message
            // Example: Log::error('PayPal HTTP Exception: ' . $e->getMessage());

            // Set an error message for the user
            // Session::flash('error', 'An error occurred while communicating with PayPal. Please try again later.');

            // Redirect back to the user plans page or any other appropriate page
            return redirect()->route('business.plans.index')->with('failed', trans("An error occurred while communicating with PayPal. Please try again later!"));
        }
    }
}
