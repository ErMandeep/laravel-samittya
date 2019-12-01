{{ html()->modelForm($logged_in_user, 'PATCH', route('admin.profile.bankupdate'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}

<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label('Account Number')->for('account_number') }}

            {{ html()->text('account_number')
                ->class('form-control')
                ->placeholder('Account Number')
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus() }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->

<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('IFSC Code'))->for('ifsc_code') }}

            {{ html()->text('ifsc_code')
                ->class('form-control')
                ->placeholder('IFSC Code')
                ->attribute('maxlength', 191)
                ->required() }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->
<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('Bank Name'))->for('bank_name') }}

            {{ html()->text('bank_name')
                ->class('form-control')
                ->placeholder(__('Bank Name'))
                ->attribute('maxlength', 13)
                ->required() }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->
<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('Account Type'))->for('account_type') }}
             {!!  Form::select('account_type',   array( 
                'Saving Acccount' => 'Saving Account', 
                'Current Account' => 'Current Account',
                ),
             $logged_in_user->account_type , ['class' => 'form-control' , 'name'=>'account_type']   );  !!} 
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->


<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('UPI ID'))->for('upi_id') }}

            {{ html()->text('upi_id')
                ->class('form-control')
                ->placeholder(__('UPI ID'))
                ->attribute('maxlength', 500)
                 }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->


<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('Phone'))->for('phone') }}

            {{ html()->text('phone')
                ->class('form-control')
                ->placeholder(__('Phone'))
                ->attribute('maxlength', 500)
                 }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->



<div class="row">
    <div class="col">
        <div class="form-group mb-0 clearfix">
            {{ form_submit(__('labels.general.buttons.update')) }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->
{{ html()->closeModelForm() }}

