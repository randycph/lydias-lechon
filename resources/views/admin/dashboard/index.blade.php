@extends('admin.layouts.app')

@section('pagetitle')
    Dashboard
@endsection

@section('pagecss')
    <style>
        .dashboard-summary {
            height: 31rem;
        }
    </style>
    
@endsection
@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Welcome, {{ auth()->user()?->firstname }}!</h4>
        </div>
        <div class="d-none d-md-block">
            <a href="{{ env('APP_URL') }}" target="_blank" class="btn btn-sm pd-x-15 btn-white btn-uppercase">
                <i data-feather="arrow-up-right" class="wd-10 mg-r-5"></i> View Website
            </a>
        </div>
    </div>

    <div class="row row-sm">
        @if (auth()->user()->has_access_to_pages_module())
            <div class="col-lg-3 col-md-6">
                <div class="card dashboard-widget">
                    <a href="{{route('pages.index')}}">
                        <div class="card-body">
                            <h4 class="tx-bold mg-b-5 lh-1"><i data-feather="layers" class="mg-r-6"></i> {{ \App\Models\Page::totalPages() }}</h4>
                            <span class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold">Total Pages</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif
        @if (auth()->user()->has_access_to_albums_module())
            <div class="col-lg-3 col-md-6">
                <div class="card dashboard-widget">
                    <a href="{{ route('albums.index') }}">
                        <div class="card-body">
                            <h4 class="tx-bold mg-b-5 lh-1"><i data-feather="image" class="mg-r-6"></i> {{ \App\Models\Album::totalAlbums() }}</h4>
                            <span class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold">Total Banner
                            Albums</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif
         @if (auth()->user()->has_access_to_news_module())
            <div class="col-lg-3 col-md-6">
                <div class="card dashboard-widget">
                    <a href="{{ route('news.index') }}">
                        <div class="card-body">
                            <h4 class="tx-bold mg-b-5 lh-1"><i data-feather="edit" class="mg-r-6"></i> {{ \App\Models\Article::totalArticles() }}</h4>
                            <span class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold">Total
                            News</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif
        @if (auth()->user()->has_access_to_video_module())
            <div class="col-lg-3 col-md-6">
                <div class="card dashboard-widget">
                    <a href="{{ route('products.index') }}">
                        <div class="card-body">
                            <h4 class="tx-bold mg-b-5 lh-1"><i data-feather="box" class="mg-r-6"></i>{{ \App\Models\Product::totalProduct() }}</h4>
                            <span class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold">Total Products</span>
                        </div>
                    </a>
                </div>
            </div>
        @else 
            <div class="col-lg-3 col-md-6"></div>
        @endif
        <div class="col-lg-12">
            <div class="row align-items-start">
                @if (auth()->user()->has_access_to_pages_module() || auth()->user()->has_access_to_albums_module() || auth()->user()->has_access_to_user_module() || auth()->user()->has_access_to_news_module())
                    <div class="col-lg-3 col-md-4">
                        <div class="card dashboard-summary mg-t-20">
                            <div class="card-header">
                                Website Summary
                            </div>
                            <div class="card-body" style="height:800px !important !important !important;">
                                @if (auth()->user()->has_access_to_pages_module())
                                    <h6><strong>Pages</strong></h6>
                                    <p><a href="{{route('pages.index.advance-search')}}?status=published"><span class="badge badge-dark">{{ \App\Models\Page::totalPublicPages() }}</span> Published Pages</a></p>
                                    <p><a href="{{route('pages.index.advance-search')}}?status=private"><span class="badge badge-dark">{{ \App\Models\Page::totalPrivatePages() }}</span> Private Pages</a></p>
                                    <p><a href="{{route('pages.index.advance-search')}}?showDeleted=on"><span class="badge badge-dark">{{ \App\Models\Page::totalDeletePages() }}</span> Deleted Pages</a></p>
                                    <hr>
                                @endif
                                @if (auth()->user()->has_access_to_albums_module())
                                    <h6><strong>Sub Banners</strong></h6>
                                        <p><a href="{{ route('albums.index') }}"><span class="badge badge-dark">{{ \App\Models\Album::totalNotDeletedAlbums() }}</span> Albums</a></p>
                                    <p><a href="{{ route('albums.index') }}"><span class="badge badge-dark">{{ \App\Models\Album::totalDeletePages() }}</span> Deleted Albums</a></p>
                                    <hr>
                                @endif
                                @if (auth()->user()->has_access_to_user_module())
                                    <h6><strong>Users</strong></h6>
                                    <p><a href="{{ route('users.index') }}"><span class="badge badge-dark">{{ \App\Models\User::activeTotalUser() }}</span> Active Users</a></p>
                                        <p><a href="{{ route('users.index') }}?showDeleted=on"><span class="badge badge-dark">{{ \App\Models\User::inactiveTotalUser() }}</span> Inactive Users</a></p>
                                    <hr>
                                @endif
                                @if (auth()->user()->has_access_to_news_module())
                                    <h6><strong>News</strong></h6>
                                    <p><a href="{{ route('news.index.advance-search') }}?status=published"><span class="badge badge-dark">{{ \App\Models\Article::totalPublishedArticles() }}</span> Published News</a></p>
                                    <p><a href="{{ route('news.index.advance-search') }}?status=private"><span class="badge badge-dark">{{ \App\Models\Article::totalDraftArticles() }}</span> Private News</a></p>
                                    <p><a href="{{ route('news.index.advance-search') }}?status=private"><span class="badge badge-dark">{{ \App\Models\Article::totalDeletedArticles() }}</span> Deleted News</a></p>

                                @endif
                            </div>
                        </div>
                        
                        <div class="dashboard-quick mg-t-20">
                            @if (auth()->user()->has_access_to_pages_module())
                                <a href="{{ route('pages.create') }}" class="btn btn-sm pd-x-15 btn-primary btn-uppercase btn-block tx-left text-white">
                                    <i data-feather="layers" class="wd-10 mg-r-5"></i> Create a Page
                                </a>
                            @endif
                            @if (auth()->user()->has_access_to_news_module())
                                <a href="{{ route('news.create') }}" class="btn btn-sm pd-x-15 btn-primary btn-uppercase btn-block tx-left text-white">
                                    <i data-feather="edit" class="wd-10 mg-r-5"></i> Create a News
                                </a>
                            @endif
                            @if (auth()->user()->has_access_to_albums_module())
                                <a href="{{ route('albums.create') }}" class="btn btn-sm pd-x-15 btn-primary btn-uppercase btn-block tx-left text-white">
                                    <i data-feather="image" class="wd-10 mg-r-5"></i> Create an Album
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        @if (auth()->user()->has_access_to_route('dashboard'))
                        <div class="card dashboard-recent mg-t-20">
                            <div class="card-header">
                                <div>Pending Payments</div>
                                <small class="text-muted">
                                    Transactions nearing their delivery dates with outstanding payments.
                                </small>
                            </div>
                            <div class="card-body pt-3">
                                <div class="list-group">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Order #</th>
                                                    <th>Customer</th>
                                                    <th>Delivery Date</th>
                                                    <th class="text-end">Total Amount</th>
                                                    <th class="text-end">Paid</th>
                                                    <th class="text-end">Balance</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($pendingPayments as $sale)
                                                    <tr>
                                                        <td>
                                                            <a target="_blank" href="{{ route('sales-transaction.view', $sale->id) }}"><strong>#{{ $sale->order_number }}</strong></a>
                                                        </td>

                                                        <td>
                                                            {{ $sale->user->name ?? '—' }}
                                                        </td>

                                                        <td>
                                                            {{ optional(safe_date($sale->nearest_delivery_date ?? $sale->items->first()->delivery_date))->format('M d, Y') ?? '—' }}
                                                        </td>

                                                        <td class="text-end">
                                                            ₱{{ number_format($sale->net_amount, 2) }}
                                                        </td>

                                                        <td class="text-end text-success">
                                                            ₱{{ number_format($sale->payments->where('status', 'PAID')->sum('amount'), 2) }}
                                                        </td>

                                                        <td class="text-end text-danger fw-semibold">
                                                            ₱{{ number_format($sale->balance($sale->id), 2) }}
                                                        </td>

                                                        <td>
                                                            <span class="badge bg-primary text-white">
                                                                {{ $sale->payments->where('status', 'PAID')->sum('amount') >= $sale->net_amount ? 'PAID' : ($sale->payments->where('status', 'PAID')->sum('amount') > 0 ? 'PARTIAL' : 'UNPAID'); }}
                                                            </span>
                                                        </td>

                                                        <td class="text-center">
                                                            <a href="{{ route('sales-transaction.index') . '/?search=' . $sale->order_number }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted py-4">
                                                            No pending payments at the moment
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        {{ $pendingPayments->links() }}
                                    </div>

                                </div>
                            </div>
                            @php
                                $ids = $pendingPayments->pluck('id')->join(',');
                            @endphp
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <span class="tx-12 position-relative" style="top: -7px"><a href="{{ route('sales-transaction.index') . '/?orderBy=date_needed&search=' . $ids }}">Show all pending payments <i class="fa fa-arrow-right"></i></a></span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="card dashboard-recent mg-t-20">
                            <div class="card-header">
                                My Recent Activities
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    @forelse($logs as $log)
                                        <p class="list-group-item list-group-item-action">
                                            <a href="{{route('settings.audit')}}?search={{$log->id}}" target="_blank">
                                                <span class="badge badge-dark">{{ ucwords($log->firstname) }} {{ ucwords($log->lastname) }}</span>
                                            </a> {{ $log->dashboard_activity }} at {{ Setting::date_for_listing($log->activity_date) }}
                                        </p>
                                    @empty
                                        No activities found!
                                    @endforelse
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <span class="tx-12"><a href="{{ route('users.show', Auth::user()->id) }}">Show all activities <i class="fa fa-arrow-right"></i></a></span>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>

    @if($tomorrowUnpaid->count() && !request()->has('page') && auth()->user()->has_access_to_route('dashboard'))
    <div class="modal fade" id="pendingPaymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        Pending Payments Alert
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="mb-2">
                        <strong>These transactions are scheduled for delivery tomorrow
                        and still have pending payments:</strong>
                    </p>

                    <ul class="mb-3">
                        @foreach($tomorrowUnpaid as $sale)
                            <li>
                                <a target="_blank" href="{{ route('sales-transaction.show', $sale->id) }}">
                                    <strong>#{{ $sale->order_number }}</strong> - {{ $sale->user->name ?? '—' }} (Balance: ₱{{ number_format($sale->balance($sale->id), 2) }})
                                </a>    
                            </li>
                        @endforeach
                    </ul>

                    <p class="text-muted mb-0">
                        Please ensure payments are settled before delivery.
                    </p>
                </div>

                <div class="modal-footer">
                    @php
                        // get all ids of tomorrow unpaid transactions
                        $ids = $tomorrowUnpaid->pluck('order_number')->join(',');
                    @endphp

                    <a 
                        href="{{ route('sales-transaction.index') . '/?orderBy=date_needed&search=' . $ids }}"
                        class="btn btn-warning">
                            View Pending Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('pagejs')
    <script src="{{ asset('lib/nestable2/jquery.nestable.min.js') }}"></script>


    @if($tomorrowUnpaid->count())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(
                document.getElementById('pendingPaymentModal')
            );
            modal.show();
        });
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!sessionStorage.getItem('shownTomorrowUnpaidModal')) {
                const modal = new bootstrap.Modal(
                    document.getElementById('pendingPaymentModal')
                );
                modal.show();
                sessionStorage.setItem('shownTomorrowUnpaidModal', '1');
            }
        });
    </script> --}}

    @endif
@endsection
