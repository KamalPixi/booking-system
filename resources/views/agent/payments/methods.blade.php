@extends('agent.master')
@section('content')
<link rel="stylesheet" href="/assets/fonts/icomoon/style.css">
<link rel="stylesheet" href="assets/css/table-style.css">

<div class="m-4">
    <h2 class="mb-3 ml-2">Bank List</h2>
    <div class="table-responsive custom-table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Bank Name</th>
                    <th scope="col">Account No.</th>
                    <th scope="col">Account Name</th>
                    <th scope="col">Branch</th>
                    <th scope="col">Routing No.</th>
                    <th scope="col">Logo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($banks as $k => $bank)
                    <tr scope="row">
                        <th scope="row pl-3">{{ $k+1 }}</th>
                        <td>{{ $bank->bank }}</td>
                        <td style="color:#6c6c6c;font-weight:600;">
                            {{ $bank->account_no }}
                        </td>
                        <td>{{ $bank->account_name }}</td>
                        <td>{{ $bank->branch }}</td>
                        <td></td>
                        <td>
                            @if(isset($bank->logo))
                                <img src="{{ $bank->logo->file }}" alt="" width="100">
                            @endif
                        </td>
                    </tr>
                    <tr class="spacer">
                        <td colspan="100"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="m-4">
    <h2 class="mb-3 ml-2">Mobile Banking</h2>
    <div class="table-responsive custom-table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Mobile Banking Name</th>
                    <th scope="col">Title</th>
                    <th scope="col">Account No.</th>
                    <th scope="col">Logo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($methods as $k => $method)
                    <tr scope="row">
                        <th scope="row pl-3">{{ $k+1 }}</th>
                        <td>{{ $method->key }}</td>
                        <td>
                            {{ $method->title }}
                        </td>
                        <td>
                            <small class="d-block" style="color:#6c6c6c;font-weight:600;">
                                {!! $method->remark !!}
                            </small>
                        </td>
                        <td>
                            @if(isset($method->logo))
                                <img src="{{ $method->logo->file }}" alt="">
                            @endif
                        </td>
                    </tr>
                    <tr class="spacer">
                        <td colspan="100"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection