<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Basic CRUD Application - jQuery EasyUI CRUD Demo</title>
	<link rel="stylesheet" type="text/css" href="/jquery-easyui-1.10.17/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="/jquery-easyui-1.10.17/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="/jquery-easyui-1.10.17/themes/color.css">
	<link rel="stylesheet" type="text/css" href="/jquery-easyui-1.10.17/demo/demo.css">
	
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
	<h2>Data Produk</h2>
	<p>Click the buttons on datagrid toolbar to do crud actions.</p>
	
	<table id="dg" title="Data Produk" class="easyui-datagrid" style="width: 700px;height:550px" method="get"
			url="/products"
			toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="true" singleSelect="true">
		<thead>
			<tr>
				<th field="name" width="50">Name</th>
				<th field="price" width="50">Price</th>
				<th field="quantity" width="50">Stock</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">Baru</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Ubah</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Hapus</a>
	</div>

	
	<div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
		<form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
			<h3>Form Produk</h3>    
			<div style="margin-bottom:10px">
				<input name="name" class="easyui-textbox" required="true" label="Nama:" style="width:100%">
			</div>
			<div style="margin-bottom:10px">
				<input name="price" class="easyui-textbox" required="true" label="Harga:" style="width:100%">
			</div>
			<div style="margin-bottom:10px">
				<input name="quantity" class="easyui-textbox" required="true" label="Jumalh:" style="width:100%">
			</div>
		</form> 
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Simpan</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Batal</a>
	</div>
    <script defer type="text/javascript" src="/jquery-easyui-1.10.17/jquery.min.js"></script>
	<script defer type="text/javascript" src="/jquery-easyui-1.10.17/jquery.easyui.min.js"></script>
    <script>
        
    </script>
	<script defer type="text/javascript">
		var url;
        var method;
		function newUser(){
			$('#dlg').dialog('open').dialog('center').dialog('setTitle','New User');
			$('#fm').form('clear');
			url = '/products';
			method = 'POST';
		}
		function editUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit User');
				$('#fm').form('load',row);
				url = '/products/'+row.id;
                method = 'PUT';
			}
		}
		function saveUser(){
            var data = $('#fm').serializeArray();
            data.push({name: '_token', value: $('meta[name="csrf-token"]').attr('content')});

            if($('#fm').form('validate')){
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function(result){
                        if (!result.status){
                            $.messager.show({
                                title: 'Error',
                                msg: result.message
                            });
                        } else {
                            $('#dlg').dialog('close');		// close the dialog
                            $('#dg').datagrid('reload');	// reload the user data
                        }
                    }
                })
            }
            
		}
		function destroyUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Apakah kamu yakin?',function(r){
					if (r){
						$.ajax({
                            type: 'DELETE',
                            url: '/products/'+row.id,
                            data : {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function(result){
                                if (!result.status){
                                    $.messager.show({
                                        title: 'Error',
                                        msg: result.message
                                    });
                                } else {
                                    // $('#dlg').dialog('close');		// close the dialog
                                    $('#dg').datagrid('reload');	// reload the user data
                                }
                            }
                        })
					}
				});
			}
		}
	</script>
</body>
</html>