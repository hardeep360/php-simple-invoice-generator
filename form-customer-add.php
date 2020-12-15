<?php

echo $form->form_open();

?>
<input type="hidden" name="_token" value="<?php echo ($_SESSION['_token'] = md5(rand(11111,999999))); ?>">


<div class="form-row">


	<div class="col-md-12">

		<?php echo $form->input_text('customer_name', 'Customer Name*','', '', ' required '); ?>

	</div>


	<div class="col-md-12">

		<?php echo $form->input_text('company_name', 'Company Name'); ?>

	</div>

</div>
<div class="form-row">
	<div class=" col-md-12">
		<?php echo $form->input_text('address1', 'Address 1'); ?>

	</div>


</div>

<div class="form-row">
	<div class=" col-md-12">
		<?php echo $form->input_text('address2', 'Address 2'); ?>

	</div>


</div>


<div class="form-row">
	<div class="col-md-6">

		<?php echo $form->input_text('email', 'Email'); ?>

	</div>

	<div class="form-group col-md-6">

		<?php echo $form->input_text('phone', 'Phone'); ?>

	</div>


</div>
<div class="form-row">
	<div class="form-group col-md-12">

		<?php echo $form->input_text('website', 'Website'); ?>

	</div>
</div>




<div class="form-row">
	<div class="form-group col-md-6">

		<select name="currency" class="form-control" >
			<option value="">Select Currency*</option>
			<?php

			foreach ( $result as $key) {
				?>
				<option value="<?php echo $key['id']; ?>"><?php echo $key['symbol']  ."   ".  $key['country']?></option>

				<?php
			}

			?>

		</select>
        </div>
            </div>



<div class="col-md-12">


	 <?php
	echo $form->input_submit('addcustomer','', 'Save', '', ' class="btn btn-primary float-right" ' );

    ?>

</div>

<?php echo $form->form_close(); ?>
