@extends('admin.layouts.app')
@section('content')
	List page
@endsection

@section('customJs')

	<script>
		function deleteCategory(categoryId){
			
			var url='{{ route("categories.delete","ID") }}';
			var newUrl = url.replace("ID",categoryId);

			if(confirm("Are you sure you want to delete")){
				$.ajax({
					url: newUrl,
					type: 'DELETE',
					data: {},
					dataType: 'json',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						window.location.href = "{{ route('categories.index') }}";
					}
				});
			}
		}
	</script>

@endsection












