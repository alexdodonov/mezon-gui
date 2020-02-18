					  <div class="x_content">
						<!--{message}
						<br /-->
						<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left col-md-{width} center-margin" action="./" method="post" enctype="multipart/form-data">
						  {fields}
						  <div class="ln_solid col-md-12"></div>
						  <div class="form-group">
							<div class="col-md-4"></div>
							<button type="submit" class="btn btn-success col-md-2" onclick="jQuery('#demo-form2').submit();">Создать</button>
							<a href="{back-link}" class="btn btn-success col-md-2">Назад</a>
						  </div>
						  <script>
						    var _creation_form_items_counter = 1;

							function add_element_by_template( Element , TemplateClass )
							{
								var		Template = jQuery( '.' + TemplateClass ).html();
								Template = Template.replace( 
									/\{_creation_form_items_counter\}/g , _creation_form_items_counter++
								);

								var		AddedElement = jQuery( Element ).parent().parent().after( Template );

								setup_toggles( jQuery( Element ).parent().parent().next().find( '[toggle-name]' ) );

								jQuery( '.multipleSelect' ).fastselect();
							}

							function remove_element_by_template( Element )
							{
								jQuery( Element ).parent().parent().remove( ':first' );
							}
						  </script>
						</form>
					  </div>
					</div>
				  </div>
				</div>
			  </div>