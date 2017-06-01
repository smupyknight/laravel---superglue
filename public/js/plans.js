function delete_feature(feature_id)
{
	if (!window.confirm('Delete Membership Feature?')) {
		return false;
	}

	$.ajax({
			url:'/admin/membership-features/delete',
			method:'post',
			data:{
				_token : $('[name="_token"]').val(),
				feature_id : feature_id,
			},
			success: function(response){
				window.location.href = '/admin/membership-features';
			}
		})
}