@extends('admin.layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Edit Business Category') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">
                    {{-- Save Business Category --}}
                    <div class="col-sm-12 col-lg-12">
                        <form
                            action="{{ route('admin.update.business-category', $business_category->business_category_id) }}"
                            method="post" class="card" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Business Category Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">

                                            {{-- Business Category Name --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label required">{{ __('Business Category Name') }}</label>
                                                    <input type="text" class="form-control" name="business_category_name"
                                                        value="{{ $business_category->business_category_name }}"
                                                        placeholder="{{ __('Business Category Name') }}" required />
                                                </div>
                                            </div>

                                            {{-- Logo Image --}}
                                            <div class="col-md-4 col-xl-4">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Business Category Logo') }}
                                                    </div>
                                                    <input type="file" class="form-control"
                                                        name="business_category_logo_url"
                                                        placeholder="{{ __('Business Category Logo') }}"
                                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                                        value="{{ $business_category->business_category_logo_url }}" />
                                                </div>
                                            </div>

                                            {{-- Description --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Description') }}</label>
                                                    <textarea class="form-control text-capitalize" name="business_category_description" rows="3"
                                                        placeholder="{{ __('Description') }}.." required> {{ $business_category->business_category_description }}</textarea>

                                                </div>
                                            </div>


                                            <div class="text-end">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-edit" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3">
                                                            </path>
                                                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3">
                                                            </path>
                                                            <line x1="16" y1="5" x2="19"
                                                                y2="8"></line>
                                                        </svg>
                                                        {{ __('Update') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>
@endsection
