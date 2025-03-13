<?php
/*---============== SANTHOSH =============---*/
namespace App\Http\Controllers;

use App\Http\Controllers\Business\BusinessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Business;

class GooglePlacesController extends Controller
{
  public function getBusinessInfo(Request $request)
  {
    $apiKey = env('GOOGLE_PLACES_API_KEY');
    $placeId = $request->query('place_id'); // Get place_id from query parameter

    if (!$placeId) {
      return response()->json(['error' => 'place_id is required'], 400);
    }

    $detailsUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid={$placeId}&key={$apiKey}";
    $response = Http::get($detailsUrl);
    return response()->json($response->json());
  }

  public function getPlaceId(Request $request)
  {
    $request->validate([
      'website' => ['required', 'regex:/^((https?:\/\/)?([\w-]+\.)+[\w-]{2,})$/'],
    ]);

    $apiKey = env('GOOGLE_PLACES_API_KEY');
    $website = urlencode($request->input('website'));

    $searchUrl = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$website}&inputtype=textquery&fields=place_id&key={$apiKey}";

    $response = Http::get($searchUrl);
    $data = $response->json();

    // Extract the place_id if it exists
    if (isset($data['candidates'][0]['place_id'])) {
      $placeId = $data['candidates'][0]['place_id'];
      $detailsUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid={$placeId}&key={$apiKey}";

      $response1 = Http::get($detailsUrl);
      // Extract data from the response
      $result = $response1->json()['result'] ?? [];

      $fields = [
        'business_name' => $result['name'] ?? null,
        'business_website_url' => $result['website'] ?? null,
        'business_phone' => $result['international_phone_number'] ?? null,
        'business_address' => $result['formatted_address'] ?? null,
        'business_country' => null,
        'business_state' => null,
        'business_city' => null,
        'business_category_id' => null,
      ];

      foreach ($result['address_components'] ?? [] as $component) {
        if (in_array('country', $component['types'])) {
          $fields['business_country'] = $component['long_name'];
        }
        if (in_array('administrative_area_level_1', $component['types'])) {
          $fields['business_state'] = $component['long_name'];
        }
        if (in_array('administrative_area_level_3', $component['types'])) {
          $fields['business_city'] = $component['long_name'];
        }
      }



      if (!empty($result['photos'])) {
        // $photoReference = $result['photos'][0]['photo_reference']; // Get first image

        $largestImage = collect($result['photos'])->sortByDesc('width')->first();
$photoReference = $largestImage['photo_reference'] ?? null;

// dd($largestImage);

        $coverImageUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference=$photoReference&key=$apiKey";

// dd($coverImageUrl);

      } else {
        $domain = $request->getHost();
        $coverImageUrl = $domain . "/images/business/cover_images/default-image.jpg"; // Fallback image
      }

      $fields['business_cover_image_url'] = $coverImageUrl;
      // Convert the response into a request object
      $businessRequest = new Request($fields);

      // Call saveBusiness function in BusinessController
      $businessController = new BusinessController();
      return $businessController->saveBusiness($businessRequest);
    } else {
      return response()->json(['error' => 'place_id is required'], 400);
    }
  }
}
/*---============== SANTHOSH =============---*/