@extends('layouts.classic')

{{-- Custom JS --}}
@section('custom-css')
<link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
@endsection

@section('content')
<div>
  @foreach ($pages as $page)
  <?php $page->page_content = htmlspecialchars_decode($page->page_content); ?>
  @if ($loop->iteration == 2)
  @if ($business_categories->count() > 0)
  <section class=" overflow-hidden mb-10 relative">
    <img class="absolute top-0 left-0" src="../../home-assets/images/headers/gradient4.svg" />
    <div class="container px-4 mx-auto">
      <h2 class="mb-6 text-6xl md:text-7xl xl:text-7xl font-bold font-heading tracking-px-n leading-none">
        {{ __('Business Categories') }}
      </h2>
      <p class="mb-20 text-lg text-gray-400 font-medium leading-normal w-full">
        {{ __('Discover a wide range of business categories to explore and find the services that meet your needs. From retail to professional services, we provide easy access to trusted businesses across multiple industries.') }}
      </p>

      <!-- Swiper -->
      <div class="swiper-container">
        <div class="swiper-wrapper">
          @foreach ($business_categories as $business_category)
          <a href="{{ route('web.businesses', ['business_category_slug' => $business_category->business_category_slug]) }}" class="swiper-slide p border-2 shadow-sm border-{{ $config[11]->config_value }}-100 py-4 rounded-2xl">
            <div class="py-4 h-full rounded-xl">
              <div class="flex flex-col items-center">
                <!-- Circular Image -->
                <div class="w-24 h-24 overflow-hidden rounded-full">
                  <img class="w-full h-full object-cover transform hover:scale-105 transition ease-in-out duration-1000" src="{{ asset($business_category->business_category_logo_url) }}" alt="{{ $business_category->business_category_name }}">
                </div>
                <!-- Name below Image -->
                <div class="w-36 lg:w-52">
                  <p class="text-2xl text-center font-bold mt-4 truncate overflow-hidden whitespace-nowrap">
                    {{ __($business_category->business_category_name) }}
                  </p>
                </div>
              </div>
            </div>
          </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif
  @endif

  {{-- Pricing --}}
  @if ($loop->iteration == 4)
  <section x-data="{ toggle: false }" class="py-20 relative overflow-hidden">
    <img class="absolute top-0 left-0" src="../../home-assets/images/headers/gradient4.svg" alt="">
    <div class="container px-4 mx-auto" id="pricing">
      <h2 class="text-center mb-20 text-6xl md:text-7xl xl:text-7xl font-bold font-heading tracking-px-n leading-none">
        {{ __('Pricing') }}
      </h2>
      <div class="overflow-hidden">
        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4">
          @foreach ($plans as $plan)
          <div class="mb-4">
            <div class="px-9 pt-8 pb-11 bg-white bg-opacity-90 border border-{{ $config[11]->config_value }}-200 rounded-3xl" style="backdrop-filter: blur(46px);">
              {{-- Plan Name & Desc --}}
              <span class="mb-3 inline-block text-sm text-{{ $config[11]->config_value }}-600 font-semibold uppercase tracking-px leading-snug">{{ __($plan->plan_name) }}</span>
              <p class="mb-6 text-gray-500 font-medium leading-relaxed">
                {{ __($plan->plan_description) }}
              </p>
              {{-- Price --}}
              <h3 class="mb-1 text-3xl text-gray-900 font-bold leading-tight">
                <span>{{ $currency->symbol }}{{ $plan->plan_price }}</span>
                <span class="text-gray-400">/
                  @if ($plan->plan_validity == 9999)
                  {{ __('Forever') }}
                  @elseif ($plan->plan_validity == 31)
                  {{ __('Month') }}
                  @elseif ($plan->plan_validity == 366)
                  {{ __('Year') }}
                  @elseif ($plan->plan_validity > 1)
                  {{ $plan->plan_validity . ' ' . __('Days') }}
                  @endif
                </span>
              </h3>

              {{-- Get started now button --}}
              @guest
              <button class="mt-5 mb-9 py-4 px-9 w-full font-medium border border-{{ $config[11]->config_value }}-300 hover:border-{{ $config[11]->config_value }}-400 rounded-xl focus:ring focus:ring-gray-50 bg-white hover:bg-gray-50 transition ease-in-out duration-200" type="button" onclick="window.location.href='/register?type=business'">
                {{ __('Get Started Now') }}
              </button>
              @else
              @if (Auth::user()->role == 1)
              <button class="mt-5 mb-9 py-4 px-9 w-full font-medium border border-{{ $config[11]->config_value }}-300 hover:border-{{ $config[11]->config_value }}-400 rounded-xl focus:ring focus:ring-gray-50 bg-white hover:bg-gray-50 transition ease-in-out duration-200" type="button" onclick="window.location.href='/admin/plans'">
                {{ __('Get Started Now') }}
              </button>
              @endif
              @if (Auth::user()->role == 2)
              <button class="mt-5 mb-9 py-4 px-9 w-full font-medium border border-{{ $config[11]->config_value }}-300 hover:border-{{ $config[11]->config_value }}-400 rounded-xl focus:ring focus:ring-gray-50 bg-white hover:bg-gray-50 transition ease-in-out duration-200" type="button" onclick="window.location.href='/business/plans'">
                {{ __('Get Started Now') }}
              </button>
              @endif
              @if (Auth::user()->role == 3 || Auth::user()->role == 4)
              <button class="mt-5 mb-9 py-4 px-9 w-full font-medium border border-{{ $config[11]->config_value }}-300 hover:border-{{ $config[11]->config_value }}-400 rounded-xl focus:ring focus:ring-gray-50 bg-white hover:bg-gray-50 transition ease-in-out duration-200" type="button">
                {{ __('Get Started Now') }}
              </button>
              @endif
              @endguest


              <ul>
                @php
                $features = is_string($plan->plan_features)
                ? json_decode($plan->plan_features, true)
                : $plan->plan_features;
                @endphp
                {{-- Number of businesses allowed --}}
                <li class="mb-4 flex items-center">
                  <svg class="mr-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.16699 10.8333L7.50033 14.1666L15.8337 5.83325" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                  <p class="font-semibold leading-normal">
                    {{ isset($features['no_of_businesses']) ? htmlspecialchars($features['no_of_businesses']) . ' ' . (htmlspecialchars($features['no_of_businesses']) == 1 ? __('Business Allowed') : __('Businesses Allowed')) : '' }}
                  </p>
                </li>
                {{-- Number of services allowed --}}
                <li class="mb-4 flex items-center">
                  <svg class="mr-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.16699 10.8333L7.50033 14.1666L15.8337 5.83325" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                  <p class="font-semibold leading-normal">
                    {{ isset($features['no_of_services']) ? htmlspecialchars($features['no_of_services']) . ' ' . (htmlspecialchars($features['no_of_services']) == 1 ? __('Service Allowed') : __('Services Allowed')) : '' }}
                  </p>
                </li>
                {{-- Number of employees allowed --}}
                <li class="mb-4 flex items-center">
                  <svg class="mr-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.16699 10.8333L7.50033 14.1666L15.8337 5.83325" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                  <p class="font-semibold leading-normal">
                    {{ isset($features['no_of_employees']) ? htmlspecialchars($features['no_of_employees']) . ' ' . (htmlspecialchars($features['no_of_employees']) == 1 ? __('Employee Allowed') : __('Employees Allowed')) : '' }}
                  </p>
                </li>
                {{-- Number of bookings allowed --}}
                <li class="mb-4 flex items-center">
                  <svg class="mr-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.16699 10.8333L7.50033 14.1666L15.8337 5.83325" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                  <p class="font-semibold leading-normal">
                    {{ isset($features['no_of_bookings']) ? htmlspecialchars($features['no_of_bookings']) . ' ' . (htmlspecialchars($features['no_of_bookings']) == 1 ? __('Booking Allowed') : __('Bookings Allowed')) : '' }}
                  </p>
                </li>
                {{-- Customer support --}}
                <li class="flex items-center">
                  @if ($plan->is_customer_support == 1)
                  <svg class="mr-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.16699 10.8333L7.50033 14.1666L15.8337 5.83325" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                  @else
                  <svg class="mr-2.5 ml-0.5" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4L16 16" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16 4L4 16" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  @endif
                  <p class="font-semibold leading-normal">
                    {{ __('Customer Support') }}
                  </p>
                </li>
              </ul>
            </div>
          </div>
          @endforeach
          <div class="mb-4">
            <div class="px-9 pt-8 pb-11 h-full bg-white bg-opacity-90 border border-{{ $config[11]->config_value }}-200 rounded-3xl" style="backdrop-filter: blur(46px)">
              <span class="mb-3 inline-block text-sm text-{{ $config[11]->config_value }}-600 font-semibold uppercase tracking-px leading-snug">{{ __('Enterprise') }}</span>
              <p class="mb-6 text-gray-500 font-medium leading-relaxed">
                {{ __('Contact Admin') }}
              </p>
              <h3 class="mb-1 text-4xl text-gray-900 font-bold leading-tight">
                {{ __('Contact') }}
              </h3>
              <p class="mb-8 text-sm text-gray-500 font-medium leading-relaxed">
                {{ __('for custom offer') }}
              </p>
              <button class="mb-9 py-4 px-9 w-full font-medium border border-{{ $config[11]->config_value }}-300 hover:border-{{ $config[11]->config_value }}-400 rounded-xl focus:ring focus:ring-gray-50 bg-white hover:bg-gray-50 transition ease-in-out duration-200" type="button">
                {{ __('Get Started Now') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif
  {{-- Contents from db --}}
  @foreach (preg_split('/(<[^>]*>)/', $page->page_content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
    @if (strpos($part, '<')===0) {!! __($part) !!} @else {{ __($part) }} @endif @endforeach @endforeach </div>

      {{-- Custom JS --}}
      @section('custom-js')
      {{-- Swiper JS --}}
      <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
      <script>
        var swiper = new Swiper('.swiper-container', {
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          breakpoints: {
            300: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            768: {
              slidesPerView: 4,
              spaceBetween: 30,
            },
            1024: {
              slidesPerView: 5,
              spaceBetween: 40,
            },
          },
        });

        /*---============== SANTHOSH =============---*/
        // Businesses
        document.addEventListener('DOMContentLoaded', function() {
          "use strict";

          const businesses = @json($business_array);
          const serviceInput = document.getElementById('service-input');
          const suggestionsContainer = document.getElementById('service-suggestions');

          serviceInput.addEventListener('input', function() {
            const filter = serviceInput.value.toLowerCase();
            suggestionsContainer.innerHTML = ''; // Clear previous suggestions

            if (filter) {
              const filteredBusinesses = businesses.filter(business =>
                business.business_website_url.toLowerCase().includes(filter)
              );

              if (filteredBusinesses.length > 0) {
                suggestionsContainer.classList.remove('hidden');

                filteredBusinesses.forEach(business => {
                  const suggestionItem = document.createElement('div');
                  suggestionItem.classList.add('p-2', 'hover:bg-gray-200',
                    'cursor-pointer');

                  suggestionItem.textContent = business.business_website_url;
                  suggestionItem.dataset.slug = business.business_website_url;

                  suggestionItem.addEventListener('click', function() {
                    // Fill the input with the selected suggestion's URL
                    serviceInput.value = this
                      .textContent; // Set the input's value to the text of the suggestion
                    serviceInput.dataset.slug = this.dataset.slug; // Store the slug for later use
                    suggestionsContainer.innerHTML =
                      ''; // Clear the suggestion dropdown
                    suggestionsContainer.classList.add(
                      'hidden'); // Hide the suggestions

                    window.location.href = `/b/${serviceInput.value}`;
                    /*---=========== SANTHOSH =============---*/

                  });

                  suggestionsContainer.appendChild(suggestionItem);
                });
              } else {
                const noResultsItem = document.createElement('div');
                noResultsItem.classList.add('p-2', 'text-gray-500');
                noResultsItem.textContent = 'No results found';
                suggestionsContainer.appendChild(noResultsItem);
                suggestionsContainer.classList.remove('hidden');
              }
            } else {
              suggestionsContainer.classList.add('hidden');
            }
          });

          // Hide suggestions when clicking outside
          document.addEventListener('click', function(event) {
            if (!serviceInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
              suggestionsContainer.classList.add('hidden');
            }
          });

          // Find business button
          document.getElementById('find-button').addEventListener('click', function() {

            /*---============== SANTHOSH =============---*/
            let selectedSlug = ""; // Declare outside

            if (serviceInput.value) {
              selectedSlug = serviceInput.value;
            } else {
              // Get the input element
              let inputElement = document.getElementById("service-input");

              // Get the 'data-slug' attribute value
              selectedSlug = inputElement.getAttribute("data-slug");
            }

            if (selectedSlug != undefined) {
              let url = '';

              if (!selectedSlug.startsWith("https://www.")) {
                // Ensure "https://www." is added correctly
                if (selectedSlug.startsWith("www.")) {
                  url = "https://" + url;
                } else if (selectedSlug.startsWith("http://")) {
                  url = selectedSlug.replace("http://", "https://www.");
                } else if (selectedSlug.startsWith("https://")) {
                  url = selectedSlug.replace("https://", "https://www.");
                } else {
                  url = "https://www." + selectedSlug;
                }
              } else {
                url = selectedSlug;
              }

              url = url.replace(/\/$/, '');
              window.location.href = `/b/${url}`;

            } else {
              const errorMessage = document.getElementById('error-message');
              errorMessage.innerHTML = `{{ __('Please enter a valid business website URL.') }}`;
              errorMessage.classList.remove('hidden');
            }
            /*---============== SANTHOSH =============---*/

          });

        });
      </script>
      @endsection
      @endsection