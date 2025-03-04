@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('mailing-list.campaigns.index') }}">Manage Campaigns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create a Campaign</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create a Campaign</h4>
            </div>
        </div>

        <form method="post" class="row row-sm" action="{{ route('mailing-list.campaigns.store') }}">
            @csrf
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">Campaign Name *</label>
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @hasError(['inputName' => 'name'])
                    @endhasError
                </div><div class="form-group">
                    <label class="d-block">Sender Name *</label>
                    <input name="from_name" type="text" class="form-control @error('from_name') is-invalid @enderror" value="{{ old('from_name') }}" required>
                    @hasError(['inputName' => 'from_name'])
                    @endhasError
                </div>
                <div class="form-group">
                    <label class="d-block">Sender Email *</label>
                    <input name="from_email" type="email" class="form-control @error('from_email') is-invalid @enderror" value="{{ old('from_email') }}" required>
                    @hasError(['inputName' => 'from_email'])
                    @endhasError
                </div>
                <div class="form-group">
                    <label class="d-block">Subject *</label>
                    <input name="subject" type="text" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                    @hasError(['inputName' => 'subject'])
                    @endhasError
                </div>

                <div class="form-group">
                    <label class="d-block">Recipient <span class="tx-color-03">Note: In excess of 300 recipients may cause delay in delivering your message.</span></label>
                    <select id="recipients" name="recipients[]" class="form-control @error('recipients') is-invalid @enderror" data-style="btn btn-outline-light btn-md btn-block tx-left" multiple>
                        @foreach ($subscribers as $subscriber)
                            <option value="{{ $subscriber->id }}" @if (in_array($subscriber->id, old('recipients', []))) selected @endif>{{ $subscriber->email_with_name() }}</option>
                        @endforeach
                    </select>
                    @hasError(['inputName' => 'recipients'])
                    @endhasError
                    @hasError(['inputName' => 'recipients.*'])
                    @endhasError
                </div>
                <div class="form-group">
                    <label class="d-block">Recipient Group</label>
                    <select id="recipientGroups" name="recipient_groups[]" class="form-control @error('recipients') is-invalid @enderror" data-style="btn btn-outline-light btn-md btn-block tx-left" multiple>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" @if (in_array($group->id, old('recipient_groups', []))) selected @endif>{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @hasError(['inputName' => 'recipient_groups'])
                    @endhasError
                    @hasError(['inputName' => 'recipient_groups.*'])
                    @endhasError
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="d-block">Content *</label>
                    <textarea name="content" id="cke_editor1" rows="10" cols="80" required>{{ old('content') }}</textarea>
                    @hasError(['inputName' => 'content'])
                    @endhasError
                    <span class="invalid-feedback" role="alert" id="contentRequired" style="display: none;">
                        <strong>The content field is required</strong>
                    </span>
                </div>
            </div>
            <div class="col-lg-12 mg-t-10 pd-b-40">
                <input class="btn btn-primary btn-sm tx-uppercase tx-semibold" name="submit" type="submit" value="save only">
                <input class="btn btn-primary btn-sm tx-uppercase tx-semibold" name="submit" type="submit" value="save and send">
                <a  href="{{ route('mailing-list.campaigns.index') }}" class="btn btn-outline-secondary btn-sm tx-uppercase tx-semibold">Cancel</a>
            </div>
        </form>

    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('#recipients').select2({ closeOnSelect: false });
        $('#recipientGroups').select2({ closeOnSelect: false });
        $('#recipients').trigger('change');
        $('#recipientGroups').trigger('change');
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUpload: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token',
            allowedContent: true,

        };
        let editor = CKEDITOR.replace('content', options);
        editor.on('required', function (evt) {
            if ($('.invalid-feedback').length == 1) {
                $('#contentRequired').show();
            }
            $('#cke_editor1').addClass('is-invalid');
            evt.cancel();
        });
    </script>
@endsection
