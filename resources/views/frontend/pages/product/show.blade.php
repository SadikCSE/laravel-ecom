@extends('frontend.layouts.master')
@section('title')
  {{ $product->title }}| Ecommerce in Bangladesh
@endsection
@section('content')

    <!-- Start Sidebar + Content -->
    <div class='container margin-top-20'>
        <div class="row">
            <div class="col-md-6">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @php $i = 1; @endphp
                        @foreach ($product->images as $image)
                            <div class="product-item carousel-item {{ $i == 1 ? 'active':'' }}">
                                <img class="d-block w-100" src="{!! asset('images/products/'.$image->image) !!}" alt="First slide">
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="widget">
                    <h3> {{ $product->title }}</h3>
                    <hr>
                    <h3> Taka {{ $product->price }} </h3>
                    <h3>Category <span class="badge badge-primary">{{ $product->category->name }} </span></h3>
                    <h3>
                        <span class="badge badge-primary">
              {{ $product->quantity  < 1 ? 'No Item is Available' : $product->quantity.' item in stock' }}
            </span>
                    </h3>


                    <div class="product-description">
                        {!! $product->description !!}
                    </div>
                </div>
                <div class="widget">

                </div>
            </div>


        </div>
    </div>

    <!-- End Sidebar + Content -->
@endsection

