@extends('frontend.layouts.master')

@section('content')

    <!-- Start Sidebar + Content -->
<div class='container margin-top-20'>
    <div class="row">


        <div class="col-md-12">
          <div class="widget">
           <h3>All Products</h3>
              @include('frontend.pages.product.partials.all_products')
            </div>
            <div class="widget">

            </div>
        </div>


    </div>
</div>

    <!-- End Sidebar + Content -->
@endsection

