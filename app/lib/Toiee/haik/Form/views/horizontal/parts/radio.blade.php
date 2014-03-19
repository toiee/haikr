<div class="form-group{{ isset($error) ? ' has-error' : '' }}">
  <label for="haik_form_{{ $id }}_{{{ $name }}}" class="control-label col-sm-3">
    {{{ $label }}} @if($required) <span class="haik-form-required">*</span>@endif
  </label>
  <div class="col-sm-9">
  @foreach($options as $i => $option)
    @if ($option === '') @continue @endif
    @if ($valign === 'vertical')
    <label class="radio-inline">
    @else
    <div class="radio">
      <label>
    @endif
        <input type="radio" name="data[{{{ $name }}}][]" value="{{{ $option }}}" id="haik_form_{{ $id }}_{{{ $name }}}"@if ($option === $value) checked@endif>{{{ $option }}}
    @if ($valign === 'vertical')
    </label>
    @else
     </label>
    </div>
    @endif
  @endforeach
    @if (isset($help))
    <span class="help-block">
      {{{ $help }}}
    </span>
    @endif
  </div>
</div>
