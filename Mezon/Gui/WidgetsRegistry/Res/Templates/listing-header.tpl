
<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>{list-title}</h3>
		</div>
	</div>

	<div class="clearfix"></div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>{list-description}</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form method="post">
						<div class="table-responsive">
							{action-message} <a href="{create-page-endpoint}"
								class="btn btn-success" title="Создать">Создать</a>{header-actions}
							<table class="table table-striped jambo_table bulk_action">
								<thead>
									<tr class="headings">{cells}
									</tr>
								</thead>

								<tbody>