<x-filament-panels::page>
    {{-- Mobile Warning Message - Shows only on mobile/tablet --}}
    <div id="mobile-warning" style="display: none;" class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-6 mb-6 mx-4">
        <div class="flex items-start gap-4">
            <svg class="w-8 h-8 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="text-lg font-bold text-yellow-800 mb-2">Mobile Device Detected</h3>
                <p class="text-yellow-700 font-medium">
                    For the best viewing and signing experience, please open this proposal on a desktop or laptop computer.
                </p>
            </div>
        </div>
    </div>
    <style>
        /* Base Styles */
        .custom_sign_btn {
            width: 36%;
            background-color: #3890fe;
            padding: 10px;
            border-radius: 4px;
            color: #fff;
        }

        header.fi-header {
            display: none;
        }

        .with-main-bg {
            width: 100%;
            max-width: 210mm;
            /* Standard A4 width */
            margin: 0 auto;
            background-position: center;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .info-inner strong {
            margin-right: 0;
        }

        .inner-container {
            padding: 2rem;
            padding-bottom: 8rem;
            /* Reserve space for bottom-area-fixed */
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Content Sections */
        .content-right-start {
            padding: 1.5rem;
            color: #000;
            margin-bottom: 1rem;
            /* Base margin to prevent overlap */
        }

        .content-right-start.abt_company {
            background-color: #eef5ffe3;
            padding: 2rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            margin-bottom: 2rem;
            /* Extra margin to prevent overlap */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Typography */
        .proposal_name,
        .intro {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            color: #000;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }

        .proposal_name {
            text-transform: uppercase;
            line-height: 1.3;
        }

        /* Customer Info */
        .customer-info,
        .company-info {
            margin-top: 1.5rem;
        }


        .customer-info h2,
        .company-info h3 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a1a1a;
            text-transform: uppercase;
        }

        .info-inner {
            line-height: 2;
            /* margin-bottom: 0.75rem; */
            display: flex;
            /* justify-content: space-between; */
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .info-inner:last-child {
            border-bottom: none;
        }

        .info-inner span:first-child {
            font-weight: 500;
            color: #333;
            font-size: 13px;
        }

        .info-inner span {
            font-size: 13px;
        }

        /* Bottom Fixed Elements */
        .bottom-area-fixed {
            position: absolute;
            bottom: 1.5rem;
            left: 2rem;
            right: 2rem;
            padding: 1rem 0;
            border-top: 2px dotted rgba(255, 255, 255, 0.8);
        }

        .bottom-area-fixed p {
            color: #fff;
            margin: 0.25rem 0;
            font-size: 0.875rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .bottom-logo-fixed {
            width: 150px;
            position: absolute;
            bottom: 2.5rem;
            left: 2rem;
            opacity: 0.9;
        }

        .bottom-logo-fixed img {
            filter: brightness(100);
            width: 100%;
            height: auto;
        }

        /* Dividers */
        .b_bottom_dotted,
        hr.b_bottom_dotted {
            margin: 1.5rem 0;
            border: none;
            border-top: 2px dotted rgba(0, 0, 0, 0.2);
        }

        /* Sections */
        .sec-service {
            margin-top: 2.5rem;
        }

        h3.intro {
            text-transform: uppercase;
        }

        .intro-inner {
            line-height: 1.8;
            color: #333;
        }

        /* Content Font Size */
        p,
        li,
        .intro-inner,
        .company-info,
        .dev_process,
        .terms {
            font-size: 13px;
        }

        .intro-inner p {
            margin-bottom: 1rem;
            color: #000;
            text-align: justify;
            font-size: 13px;
        }

        /* Services List */
        .company-info ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin: 1rem 0;
            line-height: 2;
            font-size: 13px;
        }

        .company-info ul li {
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 13px;
        }

        /* Terms Section */
        .terms ul {
            padding-left: 1.5rem;
            line-height: 1.8;
            font-size: 13px;
        }

        .terms ul li {
            margin-bottom: 1rem;
            color: #333;
            font-size: 13px;
        }

        .terms h4 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }

        .terms p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
            color: #333;
            font-size: 13px;
        }

        /* Development Process */
        .right-area-dev {
            text-align: right;
            margin-top: 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .right-area-dev p {
            color: #000;
            line-height: 1.8;
            text-align: justify;
            max-width: 50%;
            margin-left: auto;
            font-size: 13px;
        }

        .dev_process ul {
            list-style-type: decimal;
            padding-left: 1.5rem;
            line-height: 2;
            font-size: 13px;
        }

        .dev_process ul li {
            margin-bottom: 0.75rem;
            color: #333;
            font-size: 13px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #f8f9fa;
        }

        table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #1a1a1a;
            border-bottom: 2px solid #dee2e6;
        }

        table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #dee2e6;
            color: #333;
            font-size: 13px;
        }

        table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Signatures */
        .sign {
            margin: 2rem 0;
            padding: 1.5rem 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .comp-reps {
            font-weight: 500;
            color: #333;
        }

        .name-comp-reps {
            font-weight: 600;
            color: #000;
        }

        .name-comp-reps input {
            border: none;
            border-bottom: 2px solid #333;
            padding: 0.5rem;
            font-size: 1rem;
            width: 200px;
            background: transparent;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .with-main-bg {
                width: 100%;
                max-width: 100%;
                margin: 0;
                box-shadow: none;
            }

            .inner-container {
                padding: 1rem;
                padding-bottom: 1rem;
                /* Reduced since bottom-area is relative on mobile */
                min-height: auto;
            }

            .content-right-start {
                padding: 1rem;
            }

            .content-right-start.abt_company {
                padding: 1.5rem;
                margin: 1rem 0;
            }

            .proposal_name,
            .intro {
                font-size: 1.5rem;
            }

            .bottom-area-fixed {
                position: relative;
                bottom: auto;
                left: auto;
                right: auto;
                margin-top: 2rem;
                padding-top: 1rem;
                border-top: 2px dotted rgba(0, 0, 0, 0.2);
            }

            .bottom-area-fixed p {
                color: #000;
                font-size: 0.75rem;
                text-shadow: none;
            }

            .bottom-logo-fixed {
                position: relative;
                bottom: auto;
                left: auto;
                margin: 1rem 0;
                width: 120px;
            }

            .bottom-logo-fixed img {
                filter: brightness(0);
            }

            .info-inner {
                flex-direction: column;
                align-items: flex-start;
                line-height: 1.6;
            }

            .info-inner span {
                margin-bottom: 0.25rem;
            }

            .right-area-dev {
                text-align: left;
                align-items: flex-start;
            }

            .right-area-dev p {
                width: 100%;
                max-width: 100%;
                margin-left: 0;
                text-align: left;
            }

            .sign {
                flex-direction: column;
                gap: 1rem;
            }

            .name-comp-reps input {
                width: 100%;
                max-width: 300px;
            }

            table {
                font-size: 13px;
            }

            table th,
            table td {
                padding: 0.5rem;
            }

            .company-info .flex {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .inner-container {
                padding: 0.75rem;
            }

            .content-right-start {
                padding: 0.75rem;
            }

            .content-right-start.abt_company {
                padding: 1rem;
            }

            .proposal_name,
            .intro {
                font-size: 1.25rem;
            }

            .company-info h3 {
                font-size: 1.5rem;
            }

            .terms h4 {
                font-size: 1.25rem;
            }

            table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .name-comp-reps input {
                width: 100%;
            }
        }

        @media print {
            .with-main-bg {
                width: 210mm;
                max-width: 210mm;
                box-shadow: none;
            }

            .bottom-area-fixed {
                position: absolute;
            }

            .bottom-logo-fixed {
                position: absolute;
            }
        }
    </style>
    <div id="desktop-message" class="space-y-6">
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex">
                        <div class="w-full"></div>
                        <div class="">
                            <div class="content-right-start">
                                <h1 class="proposal_name"> {{ $record->proposal_name ?? 'N/A' }} </h1>

                                <hr class="b_bottom_dotted">

                                <div class="customer-info ">
                                    <h2 class="text-xl font-semibold mb-2 ">Prepared For</h2>
                                    <hr class="b_bottom_dotted">
                                    @php
                                    $record = $this->record ?? $proposal ?? null;
                                    @endphp
                                    @if($record)
                                    <div class="info-inner">
                                        <strong>Company:</strong>
                                        <span>{{ $record->client_company_name ?? 'N/A' }}</span>
                                        <hr>
                                    </div>
                                    <div class="info-inner">
                                        <strong>Client Name:</strong>
                                        <span>{{ $record->prepared_for_customer_name ?? 'N/A' }}</span>
                                        <hr>
                                    </div>
                                    <div class="info-inner">
                                        <strong>Client Phone:</strong>
                                        <span>{{ $record->prepared_for_customer_phone ?? 'N/A' }}</span>
                                        <hr>
                                    </div>
                                    <div class="info-inner">
                                        <strong>Client Address:</strong>
                                        <span>{{ $record->prepared_for_customer_address ?? 'N/A' }}</span>
                                        <hr>
                                    </div>
                                    @else
                                    <div class="info-inner">
                                        <strong>Client Company:</strong>
                                        <span> <!-- Dynamic Data --> </span>
                                        <hr>
                                    </div>
                                    <div class="info-inner">
                                        <strong>Client Name:</strong>
                                        <span> <!-- Dynamic Data --> </span>
                                        <hr>
                                    </div>
                                    <div class="info-inner">
                                        <strong>Client Phone:</strong>
                                        <span> <!-- Dynamic Data --> </span>
                                        <hr>
                                    </div>
                                    <div class="info-inner">
                                        <strong>Client Address:</strong>
                                        <span> <!-- Dynamic Data --> </span>
                                        <hr>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bottom-logo-fixed">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    </div>
                </div>
            </div>
        </div>


        <!-- project details -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex flex-col lg:flex-row">
                        <div class="hidden lg:block lg:w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right lg:text-right text-left mt-6 mb-4">
                                <h3 class="intro"> OBJECTIVE</h3>
                                {!! $record->objective !!}
                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company">
                        <div class="company-info dev_process ">
                            <h3 class="font-bold text-2xl lg:text-3xl mb-4">PROJECT SCOPE</h3>
                            {!! $record->project_description !!}


                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- project details with pricing -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex flex-col lg:flex-row">
                        <div class="hidden lg:block lg:w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right lg:text-right text-left mt-6 mb-4">
                                <h3 class="intro"> PROJECT DETAILS</h3>
                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company">
                        <div class="company-info dev_process ">
                            <h3 class="font-bold text-2xl lg:text-3xl mb-4">Pricing And Timeline</h3>
                            <div class="overflow-x-auto">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Services</th>
                                            <th>Timeline</th>
                                            <th>Sprints</th>
                                            <th>Cost per Sprint</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($record && $record->pricingQuotes)
                                        @foreach($record->pricingQuotes as $quote)
                                        <tr>
                                            <td>{{ $quote->services }}</td>
                                            <td>{{ $quote->timeline }}</td>
                                            <td>{{ $quote->quantity }}</td>
                                            <td>${{ number_format($quote->unit_price, 2) }}</td>
                                            <td>${{ number_format($quote->quantity * $quote->unit_price, 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" style="text-align: right; font-weight: bold;">Total:</td>
                                            <td style="font-weight: bold;">
                                                ${{ number_format($record->pricingQuotes->sum(function ($quote) {
                                                    return $quote->quantity * $quote->unit_price;
                                                }), 2) }}
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="4" style="text-align: center;">No pricing quotes available</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- intro -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">



                    <div class="flex">
                        <div class="w-full"></div>
                        <div class="w-full">
                            <div class="content-right-start">
                                <h3 class="intro"> Introduction</h3>
                                <div class="customer-info ">
                                    <h4 class="text-xl font-semibold mb-2 ">Dear <span>
                                            @php
                                            $record = $this->record ?? $proposal ?? null;
                                            @endphp
                                            @if($record){{ $record->prepared_for_customer_name }}@endif
                                        </span></h4>

                                    <div class="intro-inner">
                                        <p>Thank you for your time on checking our {{ $record->proposal_name ?? 'N/A' }}.

                                            We are a digital product design and development company, creating innovative software and digital products for web and mobile. Our focus is on delivering high-quality, flexible, and cost-effective solutions.

                                            Having a good mobile app will increase your sales and subscriptions. This means that your app should be stable, user-friendly, and fast.

                                            In this proposal, you'll see information about CloudTach our services, pricing, terms, and conditions.

                                            We look forward to talking with you.</p>

                                        <p>Sincerely,</p>

                                        <p><strong>Ubaid Ismail</strong></p>

                                        <p>Founder</p>

                                        <p><strong>CloudTach</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- company overview -->

        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">



                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                        <div class="hidden lg:block lg:col-span-6"></div>
                        <div class="lg:col-span-6">
                            <h3 class="intro text-right lg:text-right text-left text-black mt-6"> COMPANY OVERVIEW</h3>
                            <div class="content-right-start abt_company">
                                <div class="company-info ">
                                    <h3 class="font-bold text-2xl lg:text-3xl mb-4">About the company</h3>
                                    <p class="mb-4">At CloudTach we provide digital product design and development services, we've helped businesses across every industry turn their tech challenges into competitive advantages. Whether you need a revenue-generating website, a mobile app, or a SaaS platform that scales, we deliver solutions that actually work.</p>
                                    <div class="sec-service">
                                        <h3 class="font-bold text-3xl">OUR Services</h3>
                                        <div class="flex ">
                                            <ul>
                                                <li>Web Design & Development</li>
                                                <li>Mobile App Design & Development</li>
                                                <li>SaaS Development</li>
                                            </ul>
                                            <ul class="flex-1">
                                                <li>PaaS Development</li>
                                                <li>UI/UX Design</li>
                                                <li>AI Automation</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex flex-col lg:flex-row">
                        <div class="hidden lg:block lg:w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right lg:text-right text-left mt-6 mb-4">
                                <h3 class="intro"> OUR PROCESS</h3>
                                <p class="text-black mt-2">{{$record->proces_briefing ?? 'N/A'}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company">
                        <div class="company-info dev_process ">
                            <h3 class="font-bold text-2xl lg:text-3xl mb-4">Process Details</h3>
                            {!! $record->process_details_in_bullets !!}

                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>



        <!-- terms -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex flex-col lg:flex-row">
                        <div class="hidden lg:block lg:w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right lg:text-right text-left mt-6 mb-4">
                                <h3 class="intro"> TERMS & CONDITIONS</h3>
                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company terms">
                        <div class="company-info dev_process ">
                            <h4 class="font-bold text-xl lg:text-2xl mb-3 mt-4">I. PARTIES</h4>
                            <p class="mb-4">This {{ $record->proposal_name ?? 'N/A' }} is specially prepared for {{ $record->prepared_for_customer_name ?? 'N/A' }} (the "Client" hereinafter) by CloudTach. If the Proposal is approved, it will be accepted as an agreement between the parties and terms and conditions set out below will be binding between the Client and the Company.</p>
                            <h4 class="font-bold text-xl lg:text-2xl mb-3 mt-6">II. TERM</h4>
                            <!-- terms for fixed price project -->
                            @if($record->proposal_type === 'fixed_price')

                             <p class="mb-4">This Agreement shall enter into force on the date of signature by both parties and shall remain in effect until the completion and final delivery of the project as defined in this Agreement, unless terminated earlier by mutual written consent of the parties. Upon completion and full payment, the Agreement shall automatically expire. Any further collaboration beyond this project may be continued under a new written agreement between the parties.</p> 
                            @else

                            <p class="mb-4">
                                This Agreement shall enter into force on the date of signature by both parties and shall remain in effect on a month-to-month (subscription) basis, corresponding to the duration of active sprints or services provided. Either party may terminate the Agreement by providing 10 days’ written notice.

                                Payments will be made at the start of each sprint (month), and services will continue as long as payments are maintained. Any future or additional work beyond the current subscription period may be continued under a renewed or separate written agreement between the parties.
                            </p>
                            @endif

                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- terms -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex">
                        <div class="w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right mt-6">
                                <h3 class="intro"> TERMS & CONDITIONS</h3>

                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company terms">
                        <div class="company-info dev_process ">
                            <h4 class="font-bold text-2xl">III. PAYMENT</h4>
                            <p>The total amount due for the services selected by the Client above is <strong>
                                    ${{ $record->total_project_price ?? 'N/A' }}.
                                </strong>{!! $record->payment_terms !!}</p>

                            <h4 class="font-bold text-2xl">IV. RIGHTS & OBLIGATIONS OF THE PARTIES</h4>
                            <ul class="list-disc ">
                                <li>

                                    CloudTach undertakes to attach importance to corporate culture and ethical values ​​by paying attention to the necessary care, sensitivity and trust elements in all kinds of services it will provide, and by keeping the satisfaction of the Client in the forefront with its understanding of rich and high quality software service specific to the Client.</li>
                                <li>
                                    Company is obliged to create an environment that will provide the necessary care and trust for the data of the Client in any work to be carried out, and to convey the necessary warnings against data and other losses that the Client may suffer (Backup, security scanning, etc.).</li>
                                <li>

                                    For the purpose of fulfilling Company's contractual obligations, the Client will provide maximum convenience in the performance of examination, trial and similar services by Company's employees, and will provide the information and documents requested by Company.</li>
                                <li>

                                    Neither this Agreement nor any of the rights, interests or obligations hereunder shall be assigned by any of the parties hereto (whether by operation of law or otherwise) without the prior written consent of the other party.</li>
                            </ul>

                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- terms -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex">
                        <div class="w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right mt-6">
                                <h3 class="intro"> TERMS & CONDITIONS</h3>

                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company terms">
                        <div class="company-info dev_process ">
                            <h4 class="font-bold text-2xl">V. Termination</h4>
                            <p>In the event of the Client's default in payment, the Company may suspend the provision of services or terminate this Agreement with a 7 days written notice.
                                After the expiration or termination of this Agreement, each of the parties, upon the request of the other party, returns all kinds of information, documents and records provided by the other party. .</p>

                            <h4 class="font-bold text-2xl">VI. AMENDMENT</h4>
                            <p>This proposal can only be changed or modified by the CloudTach. A new proposal will be made if the clients wish to change the content of the document. <br> After the approval of the Client, the terms and conditions shall only be modified or changed by the written mutual consent.</p>

                            <h4 class="font-bold text-2xl">VII. INTELLECTUAL PROPERTY RIGHTS</h4>
                            @if($record->proposal_type === 'fixed_price')
                            <p>Both parties agree and acknowledge that all intellectual property rights to the source code and any related materials shall belong to the Client upon delivery and full payment.</p>
                            @else
                            <p>Both parties agree and acknowledge that all intellectual property rights to the deliverables, source code, and related materials created and fully paid for under this Agreement shall belong to the Client.
                                Ownership of any ongoing or future work will transfer only upon receipt of full payment for the respective sprint or subscription period.</p>
                            @endif
                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- terms -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex">
                        <div class="w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right mt-6">
                                <h3 class="intro"> TERMS & CONDITIONS</h3>

                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company terms">
                        <div class="company-info dev_process ">
                            <h4 class="font-bold text-2xl">VIII. CONFIDENTIALITY</h4>
                            <p>Each Party undertakes for itself and guarantees to keep confidential any information relating to or that was disclosed in preparation of or as required under this Agreement and to prevent the passing on of such information to third parties other than mandatory notification responsibilities under Law.</p>

                            <h4 class="font-bold text-2xl">IX. ENTIRE AGREEMENT</h4>
                            <p>This Agreement supersedes all prior discussions and writings and constitutes the entire agreement between the Parties.</p>

                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- terms -->
        <div class="flex min-h-screen w-100">
            <div class="with-main-bg"
                style="background-image: url('{{ asset('images/proposal-main.jpg') }}'); ">
                <div class="inner-container">

                    <div class="flex">
                        <div class="w-full"></div>
                        <div class="w-full">
                            <div class="right-area-dev text-right mt-6">
                                <h3 class="intro"> TERMS & CONDITIONS</h3>

                            </div>
                        </div>
                    </div>
                    <div class="content-right-start abt_company terms">
                        <div class="company-info dev_process ">
                            <h4 class="font-bold text-2xl">X. AGREEMENT & ACCEPTANCE</h4>
                            <p>By signing below, both parties acknowledge that they have reviewed and agreed to the terms outlined in this proposal. This proposal represents a mutual commitment to move forward with the described work and responsibilities.</p>

                            <div class="sign flex justify-between pt-4 pb-4">
                                <div class="comp-reps">
                                    Company Representative
                                </div>
                                <div class="name-comp-reps">
                                    Ubaid Ismail
                                </div>
                            </div>
                            @if($record->client_signature)
                            <p>As of the date of {{ $record->date_signed->format('F d, Y \a\t h:i A')}} This Proposal is reviewed and approved.</p>
                            @endif

                            @if($record->client_signature)
                            {{-- Already Signed --}}
                            <div class="sign flex justify-between pt-4 pb-4 bg-green-50 border border-green-200 rounded p-4">
                                <div class="comp-reps">
                                    Client Signature
                                </div>
                                <div class="name-comp-reps">
                                    <strong>{{ $record->client_signature }}</strong>
                                </div>
                            </div>
                            <p class="text-green-600 font-semibold">
                                Signed on: {{ $record->date_signed ? $record->date_signed->format('F d, Y \a\t h:i A') : 'N/A' }}
                            </p>
                            @else
                            {{-- Not Signed Yet - Show Form --}}
                            {{-- Simple text signature form --}}

                            <form wire:submit.prevent="saveSignature">
                                <div class="sign flex flex-col gap-4 pt-4 pb-4">
                                    <div class="comp-reps">
                                        Authorized Signatory (Client)
                                    </div>
                                    <div class="name-comp-reps">
                                        <input
                                            type="text"
                                            wire:model.defer="clientSignature"
                                            placeholder="Enter your full name"
                                            class="border-2 border-gray-300 rounded px-4 py-2 w-full max-w-md focus:border-blue-500 focus:outline-none"
                                            required>
                                        @error('clientSignature')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button
                                        type="submit"
                                        class="font-bold bg-primary custom_sign_btn">
                                        Sign & Submit Proposal
                                    </button>
                                </div>
                            </form>

                            @endif

                            <p class="text-center mt-6">If you have any questions, please do not hesitate to contact us at info@cloudtach.com.</p>
                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->

                    <div class="bottom-area-fixed">
                        <p>CLOUDTACH</p>
                        <p>1001 South Main Street, Montana, MT 59901</p>
                        <p>info@cloudtach.com - www.cloudtach.com</p>

                        <div class="bb_dotted"></div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <script>
        function isDesktop() {
            const userAgent = navigator.userAgent.toLowerCase();
            const mobileKeywords = ['android', 'webos', 'iphone', 'ipad', 'ipod', 'blackberry', 'windows phone'];

            // Check if any mobile keyword exists in user agent
            const isMobile = mobileKeywords.some(keyword => userAgent.includes(keyword));

            // Also check screen size
            const isLargeScreen = window.innerWidth >= 1024;

            return !isMobile && isLargeScreen;
        }

        // Show/hide messages based on device
        document.addEventListener('DOMContentLoaded', function() {
            const desktopMsg = document.getElementById('desktop-message');
            const mobileWarning = document.getElementById('mobile-warning');

            if (isDesktop()) {
                // Desktop: show desktop message, hide mobile warning
                if (desktopMsg) desktopMsg.style.display = 'block';
                if (mobileWarning) mobileWarning.style.display = 'none';
            } else {
                // Mobile: show mobile warning, hide desktop message
                if (desktopMsg) desktopMsg.style.display = 'none';
                if (mobileWarning) mobileWarning.style.display = 'block';
            }
        });

        // Update on window resize
        window.addEventListener('resize', function() {
            const desktopMsg = document.getElementById('desktop-message');
            const mobileWarning = document.getElementById('mobile-warning');

            if (isDesktop()) {
                if (desktopMsg) desktopMsg.style.display = 'block';
                if (mobileWarning) mobileWarning.style.display = 'none';
            } else {
                if (desktopMsg) desktopMsg.style.display = 'none';
                if (mobileWarning) mobileWarning.style.display = 'block';
            }
        });
    </script>
</x-filament-panels::page>