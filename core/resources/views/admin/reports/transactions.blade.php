@php
    $currentFilter = request('filter');
@endphp
@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <x-date-filter :currentFilter="$currentFilter" :currentUrl="url()->current()" />
                <div>
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <!--<div class="flex-grow-1">-->
                            <!--    <label>@lang('email')</label>-->
                            <!--    <input type="text" name="email" value="{{ request()->email }}" class="form-control">-->
                            <!--</div>-->
                            <!--<div class="flex-grow-1">-->
                            <!--    <label>@lang('mobile')</label>-->
                            <!--    <input type="number" name="mobile" value="{{ request()->mobile }}" class="form-control">-->
                            <!--</div>-->
                            <!-- <div class="flex-grow-1">
                                <label class="form-label">@lang('Currency')</label>
                                <select name="symbol" class="form-select form--control select2">
                                    <option value="">@lang('All')</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->symbol }}" @selected(request()->symbol == $currency->symbol)>
                                                {{ __($currency->symbol) }}
                                            </option>
                                        @endforeach
                                </select>
                            </div> -->
                            <div class="flex-grow-1">
                                <label>@lang('ID')</label>
                                <input type="text" name="lead_code" value="{{ request()->lead_code }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Name')</label>
                                <input type="text" name="name" value="{{ request()->name }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Type')</label>
                                <select name="trx_type" class="form-control">
                                    <option value="">@lang('All')</option>
                                    <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                    <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('TRX')</label>
                                <input type="text" name="trx" value="{{ request()->trx }}" class="form-control">
                            </div>
                            <!--<div class="flex-grow-1">-->
                            <!--    <label>@lang('Remark')</label>-->
                            <!--    <select class="form-control" name="remark">-->
                            <!--        <option value="">@lang('Any')</option>-->
                            <!--        @foreach($remarks as $remark)-->
                            <!--        <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>-->
                            <!--        @endforeach-->
                            <!--    </select>-->
                            <!--</div>-->
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45">
                                    <i class="la la-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="p-3">
                    <small>
                        @if ($transactions->firstItem())
                            <strong>{{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} of {{ $transactions->total() }}</strong>
                            
                        @endif
                    </small>
                </div>
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Currency')</th>
                                <th>@lang('TRX')</th>
                                <th>@lang('Transacted')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Post Balance')</th>
                                <th>@lang('Details')</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.detail', $trx->user->id) }}">{{ $trx->user->lead_code }}</a>
                                    </td>
                                    <td style="text-align: left !important">
                                        <span class="fw-bold" >{{ $trx->user?->fullname }}</span>
                                    </td>
                                    <td>
                                        <div class="text-end text-lg-start">
                                            <span>{{ @$trx->wallet->currency->symbol }}</span>
                                            <br>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $trx->trx }}</strong>
                                    </td>

                                    <td>
                                        {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                    </td>

                                    <td class="budget">
                                        <span class="fw-bold @if($trx->trx_type == '+')text--success @else text--danger @endif">
                                            {{ $trx->trx_type }} {{showAmount($trx->amount)}} {{ __(@$trx->wallet->currency->symbol) }}
                                        </span>
                                    </td>

                                    <td class="budget">
                                        {{ showAmount($trx->post_balance) }} {{ __(@$trx->wallet->currency->symbol) }}
                                    </td>

                                    <td>{{ __($trx->details) }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-outline--primary"
                                            data-bs-target="#actionMessageModal{{ $trx->id }}"
                                            data-bs-toggle="modal"
                                            >
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </td>
                                    <div id="actionMessageModal{{ $trx->id }}" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hide Trans {{ $trx->id }}</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                                <form
                                                    action="{{ route('admin.report.transaction.hide', $trx->id) }}"
                                                    method="POST"
                                                    >
                                                    @csrf
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                                                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
        @if ($transactions->hasPages())
        <div class="card-footer py-4">
            <div>
                <small>
                    @if ($transactions->firstItem())
                        <strong>{{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} of {{ $transactions->total() }}</strong>
                        
                    @endif
                </small>
            </div>
            <div class="d-flex justify-content-center mb-3">
                {{ paginateLinks($transactions) }}
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center gap-2">
                   {{-- {{dd(parse_url(url()->full()));}} --}}
                    @foreach (request()->query() as $key => $value)
                        @if ($key !== 'per_page')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <span for="per_page" class="per_page_span" style="font-size: 12px">View</span>
                    <select name="per_page" id="per_page" onchange="this.form.submit()" style="font-size: 14px !important; padding: 0">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }} style="font-size: 14px !important; padding: 0">5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }} style="font-size: 14px important; padding: 0">10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }} style="font-size: 14px important; padding: 0">25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }} style="font-size: 14px important; padding: 0">50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }} style="font-size: 14px important; padding: 0">100</option>
                    </select>
                    <span for="per_page" class="me-2 per_page_span" style="font-size: 12px">Per Page</span>
                </form>
            </div>
        </div>
    @endif
        </div><!-- card end -->
    </div>
</div>
@endsection
