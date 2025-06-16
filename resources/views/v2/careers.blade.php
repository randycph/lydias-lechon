@extends('layouts.guest')

@section('title', 'Careers | ')
@section('meta_description', 'Join the Lydia’s Lechon family! Explore exciting career opportunities and be part of our mission to bring joy and delicious lechon to every Filipino home. Apply now!')

@section('content')

    <div class="container">
        <div class="pt-20 pb-10 px-4">
            <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center my-10">Join the Lydia’s Lechon Family</h1>
            <p class="text-center text-base lg:text-2xl">At Lydia’s Lechon, we’re always looking for passionate individuals to join our team. Whether you’re starting your career or seeking new challenges, we offer a supportive environment where you can grow. Explore our job openings and be part of our mission to bring joy and delicious lechon to every Filipino home.</p>
        </div>
    
        <div class="relative mx-auto px-4">
    
            <div class="mx-auto rounded-xl shadow-lg overflow-hidden flex flex-col lg:flex-row">
                <!-- Top Section -->
                <div class="relative bg-green-700 text-white p-6 lg:px-20 lg:py-16 order-1 lg:order-2 w-full lg:w-1/2">
                    <h2 class="text-2xl lg:text-3xl font-medium uppercase mb-4 font-cubao">Ready to Join Our Team?</h2>
                    <p class="mb-5 text-base lg:text-xl py-4 z-20 max-w-sm">
                        Interested in a career or internship with the Lydia's Lechon family? We would love to have you join us!
                        Find your next opportunity, new jobs are posted every day. Learn more about the hottest jobs in the organic industry.
                    </p>
                    <a href="#"
                       class="bg-tertiary custom-btn btn-tertiary w-full flex justify-center  lg:w-max text-white text-base lg:text-lg font-semibold px-6 py-4 transition">
                        See Our Job Openings
                    </a>
                    <img src="{{ asset('images/careers-img.png') }}" alt="Apply here" class="z-10 hidden lg:block absolute bottom-0 right-0">
                </div>
            
                <div class="bg-white p-6 lg:px-20 lg:py-16 order-2 lg:order-1 w-full lg:w-1/2">
                    <h3 class="text-primary text-2xl lg:text-3xl font-cubao font-medium uppercase mb-4">Apply Now</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error') || $errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">
                                {{ session('error') ?? 'Please correct the errors below and try again.' }}
                            </span>
                        </div>
                    @endif

                    <form role="form" class="space-y-4"  action="{{ route('applicant') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <div class="mb-5">
                                <label for="name" class="block mb-2 font-bold text-gray-900">Full Name <span class="text-red-800">*</span> </label>
                                <input type="tel" id="name"
                                    name="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" required />
                                @error('name')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <label for="email" class="block mb-2 font-bold text-gray-900">Email Address <span class="text-red-800">*</span></label>
                                <input type="email" id="email"
                                    name="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" />
                                @error('email')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <label for="tel" class="block mb-2 font-bold text-gray-900">Contact Number <span class="text-red-800">*</span></label>
                                <input type="tel" id="tel"
                                    name="contact"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" />
                                @error('contact')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
    
                            <div class="mb-5">
                                <label for="resume" class="block mb-2 font-bold text-gray-900">Upload your CV <span class="text-red-800">*</span></label>
                                <div class="flex">
                                    <input type="file" name="resume" id="resume" accept="application/pdf" class="flex-1 border border-gray-300 rounded-l px-4 py-2.5 focus:outline-none" readonly>
                                    <label class="bg-tertiary hover:bg-secondary text-white px-4 py-2 cursor-pointer flex items-center rounded-r">
                                        Choose File
                                        <input type="file" class="hidden">
                                    </label>
                                </div>

                                @error('resume')
                                    <div class="text-red-500 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                            <div class="g-recaptcha" data-sitekey="6LcQlLoZAAAAACFNGgNr2u7TXJrxCZyWA2Xk1QQ4"></div>

                            @error('g-recaptcha-response')
                                <div class="text-red-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
            
                        <button type="submit"
                                class="w-full custom-btn btn-primary-dark bg-primary text-white font-semibold py-3 rounded-md hover:bg-primary-dark transition">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
    
            <div class="mt-10">
                <h2 class="font-cubao text-4xl lg:text-6xl text-center text-primary">Current Opening/s</h2>
                <hr class="my-5">
    
                <div class="">
                    <div class="bg-white border border-border rounded-xl p-6 mb-4">
                        <h3 class="text-xl text-primary font-semibold">Marketing Manager</h3>
                        <div class="flex gap-1 text-sm items-center text-gray-800 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            Lydia’s Lechon - Quezon City Branch
                        </div>
                        <hr class="my-4">
                        <div class="font-semibold">Qualifications</div>
                        <ul class="list-disc pl-6">
                            <li>
                                College graduate (for supervisors and marketing staff)
    
                                <ul class="list-disc pl-6">
                                    <li>At least HS graduate for other hirings</li>
                                </ul>
                            </li>
           
                            <li>
                                Preferably with one (1) year of work experience in the Food & Restaurant Industry
                            </li>
                            <li>
                                With a positive and team player attitude
                            </li>
                            <li>
                                Flexible and punctual
                            </li>
                        </ul>
    
                        <hr class="my-4">
                        <div><strong>NOTE*</strong> Applicants must have SSS, Philhealth, Pag-ibig, NBI Clearance, Vaccine Card, Birth Certificate, TOR/Diploma</div>
                    </div>
                    <div class="bg-white border border-border rounded-xl p-6 mb-4">
                        <h3 class="text-xl text-primary font-semibold">Driver (QC Branch)</h3>
                        <div class="flex gap-1 text-sm items-center text-gray-800 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            Lydia’s Lechon - Quezon City Branch
                        </div>
                        <hr class="my-4">
                        <div class="font-semibold">Qualifications</div>
                        <ul class="list-disc pl-6">
                            <li>
                                College graduate (for supervisors and marketing staff)
    
                                <ul class="list-disc pl-6">
                                    <li>At least HS graduate for other hirings</li>
                                </ul>
                            </li>
           
                            <li>
                                Preferably with one (1) year of work experience in the Food & Restaurant Industry
                            </li>
                            <li>
                                With a positive and team player attitude
                            </li>
                            <li>
                                Flexible and punctual
                            </li>
                        </ul>
    
                        <hr class="my-4">
                        <div><strong>NOTE*</strong> Applicants must have SSS, Philhealth, Pag-ibig, NBI Clearance, Vaccine Card, Birth Certificate, TOR/Diploma</div>
                    </div>
                    <div class="bg-white border border-border rounded-xl p-6 mb-4">
                        <h3 class="text-xl text-primary font-semibold">Store Supervisor (Roces Ave. Q.C. Branch)</h3>
                        <div class="flex gap-1 text-sm items-center text-gray-800 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            Lydia’s Lechon - Roces Ave. Q.C. Branch 
                        </div>
                        <hr class="my-4">
                        <div class="font-semibold">Qualifications</div>
                        <ul class="list-disc pl-6">
                            <li>
                                College graduate (for supervisors and marketing staff)
    
                                <ul class="list-disc pl-6">
                                    <li>At least HS graduate for other hirings</li>
                                </ul>
                            </li>
           
                            <li>
                                Preferably with one (1) year of work experience in the Food & Restaurant Industry
                            </li>
                            <li>
                                With a positive and team player attitude
                            </li>
                            <li>
                                Flexible and punctual
                            </li>
                        </ul>
    
                        <hr class="my-4">
                        <div><strong>NOTE*</strong> Applicants must have SSS, Philhealth, Pag-ibig, NBI Clearance, Vaccine Card, Birth Certificate, TOR/Diploma</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer-component />
    
@endsection

