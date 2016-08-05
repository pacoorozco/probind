{{-- Create / Edit Level Form --}}
@if (isset($level))
    {!! Form::model($level, array(
            'route' => array('admin.levels.update', $level),
            'method' => 'put',
            'files' => true,

            )) !!}
@else
    {!! Form::open(array(
            'route' => array('admin.levels.store'),
            'method' => 'post',
            'files' => true,

            )) !!}
@endif

<div class="box box-solid">
    <div class="box-body">

        <div class="row">
            <div class="col-xs-6">

                <!-- name -->
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name', trans('admin/level/model.name'), array('class' => 'control-label required')) !!}
                    <div class="controls">
                        {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required')) !!}
                        <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                    </div>
                </div>
                <!-- ./ name -->

                <!-- amount_needed -->
                <div class="form-group {{ $errors->has('amount_needed') ? 'has-error' : '' }}">
                    {!! Form::label('amount_needed', trans('admin/level/model.amount_needed'), array('class' => 'control-label required')) !!}
                    <div class="controls">
                        {!! Form::number('amount_needed', null, array('class' => 'form-control', 'required' => 'required', 'min' => '1')) !!}
                        <span class="help-block">{{ $errors->first('amount_needed', ':message') }}</span>
                    </div>
                </div>
                <!-- ./ amount_needed -->

                <!-- activation status -->
                <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                    {!! Form::label('active', trans('admin/level/model.active'), array('class' => 'control-label required')) !!}
                    <div class="controls">
                        {!! Form::select('active', array('1' => trans('general.yes'), '0' => trans('general.no')), null, array('class' => 'form-control', 'required' => 'required')) !!}
                        {{ $errors->first('active', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>
                <!-- ./ activation status -->

            </div>
            <div class="col-xs-6">

                <!-- image -->
                <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                    {!! Form::label('image', trans('admin/level/model.image'), array('class' => 'control-label required')) !!}
                    <p class="text-muted">{{ trans('admin/level/model.image_help') }}</p>
                    <div class="controls">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                 style="width: 150px; height: 150px;">
                                @if (isset($level))
                                    <img src="{{ $level->image->url() }}">
                                @endif
                            </div>
                            <p>
                            <span class="btn btn-default btn-file">
                                <span class="fileinput-new"><i
                                            class="fa fa-picture-o"></i> {{ trans('button.pick_image') }}</span>
                                <span class="fileinput-exists"><i
                                            class="fa fa-picture-o"></i> {{ trans('button.upload_image') }}</span>
                                {!! Form::file('image') !!}
                            </span>
                                <a href="#" class="btn fileinput-exists btn-default" data-dismiss="fileinput">
                                    <i class="fa fa-times"></i> {{ trans('button.delete_image') }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <span class="help-block">{{ $errors->first('image', ':message') }}</span>
                </div>
            </div>
            <!-- ./ image -->

        </div>
    </div>

    <div class="box-footer">
        <!-- Form Actions -->
        <a href="{{ route('admin.levels.index') }}">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> {{ trans('general.back') }}
            </button>
        </a>
        {!! Form::button(trans('button.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                <!-- ./ form actions -->
    </div>
</div>
{!! Form::close() !!}

{{-- Styles --}}
@section('styles')
        <!-- File Input -->
{!! HTML::style('vendor/jasny-bootstrap/dist/css/jasny-bootstrap.min.css') !!}
@endsection

{{-- Scripts --}}
@section('scripts')
        <!-- File Input -->
{!! HTML::script('vendor/jasny-bootstrap/dist/js/jasny-bootstrap.min.js') !!}
@endsection