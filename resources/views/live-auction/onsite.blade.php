@extends('layouts.no-layout')

@section('css_files')
    <style>
        html body {
            font-family: Oswald !important;
        }

        .text-blue {
            color: #193D7A;
        }

        .bg-light-blue {
            background-color: #EDF3FF;
        }

        .lot-list .item {
            border-bottom: 1px dashed #E2E8F0;
        }

        .lot-list .item:last-child {
            border-bottom: 0;
        }
    </style>
@endsection


@section('content')

    <body class="main" style="font-family: Oswald;">
        <div class="h-screen bg-white">
            <div class="flex flex-col h-full leading-normal  relative">
                <div class="px-4">
                    <img class="w-48 mt-2" src="https://hmr.ph/images/hmr-ph-logo.png" alt="">
                </div>
                <div class="flex h-full relative">
                    <div class="w-1/2 h-full pt-6">
                        <div class="w-full h-full relative">

                            <div class="bg-light-blue z-50 h-full absolute w-3/5 top-0 left-0"
                                style="border-top-right-radius: 50%; z-index: 50">
                            </div>
                            <div class="relative ml-24" style="z-index:99">

                                <span class="inline-block bg-white px-6 mt-10 rounded-lg shadow-sm text-blue"
                                    style="font-size: 2.8vw">Lot
                                    #69</span>

                                {{-- <div class="flex items-center justify-center bg-white w-3/4 rounded shadow-md mt-6"
                                    style=" height: 55vh;">
                                    <img src="https://www.dealerlogin.co/ph/Elmer-Chan/image_65_1496297_1.jpeg"
                                        class="rounded block w-auto "
                                        style="max-height:55vh; max-width: 97%; border: 5px solid white" alt="">
                                </div> --}}
                                <img src="https://www.dealerlogin.co/ph/Elmer-Chan/image_65_1496297_1.jpeg"
                                    class="rounded-lg block w-auto shadow-lg mt-8 "
                                    style="max-height:65vh; max-width: 92%; border: 8px solid white" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-1/2 ">
                        <div class="text-blue" style="font-size: 2.5vw; ">Asking Bid</div>
                        <div class="block font-medium text-blue" style="font-size: 5.5vw">₱ 510,000</div>
                        <div class="flex">
                            <div class="flex items-center mr-10">
                                <i class="fa-sharp fa-solid fa-gauge mr-3" style="font-size: 2.0vw;"></i>
                                <span style="font-size: 2.2vw;">58,592 KM</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fa-solid fa-gas-pump fa-4x mr-3" style="font-size: 2.0vw;"></i>
                                <span style="font-size: 2.2vw;">Diesel</span>
                            </div>
                        </div>

                        <div class="flex flex-col w-3/4 mt-8 rounded-tl rounded-tr shadow-xl lot-list">
                            <div class="flex bg-light-blue rounded-tl rounder-tr">
                                <div class="w-1/3 text-center py-2" style="font-size: 1.4vw;">Lot #</div>
                                <div class="w-1/3 text-center py-2" style="font-size: 1.4vw;">Opening</div>
                                <div class="w-1/3 text-center py-2" style="font-size: 1.4vw;">Hammer</div>
                            </div>
                            <div class="flex item">
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">65</div>
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">-</div>
                                <div class="w-1/3 py-2 text-center uppercase" style="font-size: 1.4vw;">Passed</div>
                            </div>
                            <div class="flex item">
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">66</div>
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">-</div>
                                <div class="w-1/3 py-2 text-center uppercase" style="font-size: 1.4vw;">₱ 190,000</div>
                            </div>
                            <div class="flex item">
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">67</div>
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">-</div>
                                <div class="w-1/3 py-2 text-center uppercase" style="font-size: 1.4vw;">₱ 690,000</div>
                            </div>
                            <div class="flex item">
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">68</div>
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">-</div>
                                <div class="w-1/3 py-2 text-center uppercase" style="font-size: 1.4vw;">Passed</div>
                            </div>
                            <div class="flex item rounded-bl rounder-br">
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">69</div>
                                <div class="w-1/3 py-2 text-center" style="font-size: 1.4vw;">-</div>
                                <div class="w-1/3 py-2 text-center uppercase" style="font-size: 1.4vw;">-</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 text-white rounded-tr-xl text-center py-4 w-1/2"
                    style="font-size: 2.8vw; z-index:99; background-color: rgba(0,0,0,0.5)">
                    2018 GLS Montero Sport
                </div>
            </div>
        </div>
    </body>
@endsection

@section('js_files')
    <script src="{{ mix('js/live-auction.js') }}"></script>
    {{-- <script src="{{ asset('js/nav.js') }}"></script> --}}
    <script src="https://kit.fontawesome.com/d7525606a0.js" crossorigin="anonymous"></script>
@endsection
