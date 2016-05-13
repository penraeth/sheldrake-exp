$(document).ready(function() {

	$('#data_host_open').DataTable({
		'ordering': false,
		'searching': false,
		'lengthChange': false,
		'pageLength': 5,
		'pagingType': 'simple',
		'language': {
			'info': '',
			'infoEmpty': '',
			'emptyTable': 'There are currently no records of this type to show',
			'paginate': {
				'next': 'Next&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>',
				'previous': '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp;&nbsp;Prev',
			}
		},
		'drawCallback': function(settings) {
			api = new $.fn.dataTable.Api( settings );
			if (api.page.info().pages == 0) {
				$('#panel_host_open').removeClass('panel-table-data');
				$('#panel_host_open').html('There are currently no records of this type to show');
			}
			$('#div_data_host_open').find('div.dataTables_paginate').each(function(i) {
				if (api.page.info().pages <= 1) {
					$(this).hide();
				}
			});
		}
    });
    
	$('#data_host_done').DataTable({
		'ordering': false,
		'searching': false,
		'lengthChange': false,
		'pageLength': 5,
		'pagingType': 'simple',
		'language': {
			'info': '',
			'infoEmpty': '',
			'emptyTable': 'There are currently no records of this type to show',
			'paginate': {
				'next': 'Next&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>',
				'previous': '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp;&nbsp;Prev',
			}
		},
		'drawCallback': function(settings) {
			api = new $.fn.dataTable.Api( settings );
			if (api.page.info().pages == 0) {
				$('#panel_host_done').removeClass('panel-table-data');
				$('#panel_host_done').html('There are currently no records of this type to show');
			}
			$('#div_data_host_done').find('div.dataTables_paginate').each(function(i) {
				if (api.page.info().pages <= 1) {
					$(this).hide();
				}
			});
		}
    });

	$('#data_guest_open').DataTable({
		'ordering': false,
		'searching': false,
		'lengthChange': false,
		'pageLength': 5,
		'pagingType': 'simple',
		'language': {
			'info': '',
			'infoEmpty': '',
			'emptyTable': 'There are currently no records of this type to show',
			'paginate': {
				'next': 'Next&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>',
				'previous': '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp;&nbsp;Prev',
			}
		},
		'drawCallback': function(settings) {
			api = new $.fn.dataTable.Api( settings );
			if (api.page.info().pages == 0) {
				$('#panel_guest_open').removeClass('panel-table-data');
				$('#panel_guest_open').html('There are currently no records of this type to show');
			}
			$('#div_data_guest_open').find('div.dataTables_paginate').each(function(i) {
				if (api.page.info().pages <= 1) {
					$(this).hide();
				}
			});
		}
    });

	$('#data_guest_done').DataTable({
		'ordering': false,
		'searching': false,
		'lengthChange': false,
		'pageLength': 5,
		'pagingType': 'simple',
		'language': {
			'info': '',
			'infoEmpty': '',
			'emptyTable': 'There are currently no records of this type to show',
			'paginate': {
				'next': 'Next&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>',
				'previous': '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp;&nbsp;Prev',
			}
		},
		'drawCallback': function(settings) {
			api = new $.fn.dataTable.Api( settings );
			if (api.page.info().pages == 0) {
				$('#panel_guest_done').removeClass('panel-table-data');
				$('#panel_guest_done').html('There are currently no records of this type to show');
			}
			$('#div_data_guest_done').find('div.dataTables_paginate').each(function(i) {
				if (api.page.info().pages <= 1) {
					$(this).hide();
				}
			});
		}
    });

});
