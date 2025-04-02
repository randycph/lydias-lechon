@extends('layouts.guest')

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
                    <form class="space-y-4">
                        <div>
                            <div class="mb-5">
                                <label for="name" class="block mb-2 font-bold text-gray-900">Full Name <span class="text-red-800">*</span> </label>
                                <input type="tel" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" required />
                            </div>
                            <div class="mb-5">
                                <label for="email" class="block mb-2 font-bold text-gray-900">Email Address <span class="text-red-800">*</span></label>
                                <input type="email" id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" />
                            </div>
                            <div class="mb-5">
                                <label for="tel" class="block mb-2 font-bold text-gray-900">Contact Number <span class="text-red-800">*</span></label>
                                <input type="tel" id="tel"
                                    class="bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" />
                            </div>
    
                            <div class="mb-5">
                                <label for="file" class="block mb-2 font-bold text-gray-900">Upload your CV <span class="text-red-800">*</span></label>
                                <div class="flex">
                                    <input type="text" class="flex-1 border border-gray-300 rounded-l px-4 py-2.5 focus:outline-none" readonly>
                                    <label class="bg-tertiary hover:bg-secondary text-white px-4 py-2 cursor-pointer flex items-center rounded-r">
                                        Choose File
                                        <input type="file" class="hidden">
                                    </label>
                                </div>
                            </div>
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

