@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="p-3">
                    <small>
                        @if ($pairs)
                            <strong>{{ $pairs->firstItem() }} - {{ $pairs->lastItem() }} of {{ $pairs->total() }}</strong>
                        @endif
                    </small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two highlighted-table" id="coinPairList">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Leverege')</th>
                                    <th>@lang('Lots')</th>
                                    <th>@lang('Level')</th>
                                    <th>@lang('Status')</th>
                                    <th id="thAction">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pairs as $pair)
                                @php
                                     if (isset($pair->market) && isset($pair->market->currency)) {
                                        $marketCurrency = $pair->market->currency;
                                        $marketCurrency->name = $pair->market->name;
                                    } else {
                                        $marketCurrency = null;
                                    }
                                @endphp
                                    <tr>
                                        <td>
                                            <x-currency :currency="@$pair->coin" />
                                        </td>
                                        
                                        <td>{{ @$pair->symbol }}</td>
                                        <td>{{ showAmount($pair->percent_charge_for_sell) }}</td>
                                        <td>{{ showAmount($pair->percent_charge_for_buy) }}  </td>
                                         <td>{{ showAmount($pair->level_percent) }}  </td>
                                        <td>@php  echo $pair->statusBadge @endphp</td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.coin.pair.edit', $pair->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                <!--@if ($pair->status == Status::DISABLE)-->
                                                <!--    <button class="btn btn-sm btn-outline--success ms-1" data-bs-toggle="modal" data-bs-target="#actionMessageModal{{ $pair->id }}">-->
                                                <!--        <i class="la la-eye"></i> @lang('Enable')-->
                                                <!--    </button>-->
                                                <!--@else-->
                                                <!--    <button class="btn btn-sm btn-outline--danger ms-1" data-bs-toggle="modal" data-bs-target="#actionMessageModal{{ $pair->id }}">-->
                                                <!--        <i class="la la-eye-slash"></i> @lang('Disable')-->
                                                <!--    </button>-->
                                                <!--@endif-->
                                            </div>
                                        </td>
                                        <div id="actionMessageModal{{ $pair->id }}" class="modal fade" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <span>{{ $pair->status ? 'Disable' : 'Enable' }} Symbol</span> {{ $pair->symbol }}
                                                        </h5>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                    <form
                                                        action="{{ route('admin.coin.pair.status', $pair->id) }}"
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
                        </table>
                    </div>
                </div>
                
            </div>
            @if ($pairs->hasPages())
            <div class="card-footer py-4">
                <div>
                    <small>
                        @if ($pairs->firstItem())
                            <strong>{{ $pairs->firstItem() }} - {{ $pairs->lastItem() }} of {{ $pairs->total() }}</strong>
                            
                        @endif
                    </small>
                </div>
                <div class="d-flex justify-content-center mb-3">
                    {{ paginateLinks($pairs) }}
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
        </div>
    </div>

    <div id="modal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('New Crypto Currency')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview"
                                                style="background-image: url({{ getImage('', getFileSize('currency')) }})">
                                                <button type="button" class="remove-image"><i
                                                        class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" hidden class="profilePicUpload" id="profilePicUpload1"
                                                accept=".png, .jpg, .jpeg" name="image">
                                            <label for="profilePicUpload1"
                                                class="bg--primary mt-3">@lang('Select Logo')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Type')</label>
                                    <select class="form-control" name="type">
                                        <option value="1">@lang('Crypto')</option>
                                        <option value="2">@lang('Fiat')</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Symbol')</label>
                                    <input type="text" class="form-control" name="symbol" value="{{ old('symbol') }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Rank')</label>
                                    <input type="number" class="form-control" name="rank" value="{{ old('rank') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection


@push('style')
    <style>
        #coinPairList{
            width: 99%;
        }
       #coinPairList td[data-label="Action"], #thAction {
            text-align: center !important;
        }
    </style>
@endpush
@push('breadcrumb-plugins')
<x-search-form placeholder="Name,Symbol...." />
<!--@if(can_access('add-symbol'))-->
<!--    <a href="{{ route('admin.coin.pair.create') }}" class="btn btn-outline--primary addBtn h-45">-->
<!--        <i class="las la-plus"></i>@lang('New Symbol')-->
<!--    </a>-->
<!--@endif-->

@endpush
