@extends('layouts/app')
@section('css')
    <style>
        .align-end{
            heigth: 100%;
            display: flex;
            flex-direction: column;
            justify-content: end;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }
        table th{
            text-align: start;
            border-width: 1px;
            padding: .5rem .75rem
        }

        table td{
            text-align: start;
            border-width: 1px;
            padding: .5rem .75rem
        }

        .btn{
            border-width: 1px;
            cursor: pointer;
            border-width: 1px;
            padding: .5rem .75rem;
            border-radius: 10px;
            margin: 2px;
        }
        .btn-danger{
            color: white;
            background-color: rgb(239 68 68);
        }

        .btn-primary{
            color: white;
            background-color: rgb(94, 114, 228);
        }

        .btn-white{
            color: black;
            background-color: white;
        }

        .btn:hover{
            filter: invert(20%);
        }

        #navigation_table{
            padding: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection
@section('title')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Cadastro de Usuários
</h2>
@endsection
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Cadastro de Usuários -->
                    <div>
                        <form id="form_user">
                            <input type="hidden" name="id" />
                            <div>
                                <div class="w-full border-b">
                                    <span class="font-semibold text-xl">Usuário</span>
                                </div>
                                <div class="mt-4 flex">
                                    <div class="p-2 w-full">
                                        <label>Nome</label>
                                        <x-text-input
                                            class="block mt-1 w-full"
                                            type="text"
                                            name="name"
                                            required
                                        />
                                    </div>
                                    <div class="p-2  w-full">
                                        <label>Email</label>
                                        <x-text-input
                                            class="block mt-1 w-full"
                                            type="text"
                                            name="email"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="p-2  w-full">
                                        <label>Senha</label>
                                        <x-text-input
                                            class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            autocomplete="new-password"
                                        />
                                    </div>
                                    <div class="p-2  w-full">
                                        <label>Confirmação de senha</label>
                                        <x-text-input
                                            class="block mt-1 w-full"
                                            type="password"
                                            name="password_confirmation"
                                            autocomplete="confirm-password"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="w-full border-b flex justify-between">
                                    <span class="font-semibold text-xl">Telefones</span>
                                </div>
                                <div class="w-full text-right mt-3">
                                    <button class="btn btn-white" id="button_add_phone" type="button">
                                        Adicionar Telefone
                                    </button>
                                </div>
                                <div id="forms_phones"></div>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button class="btn btn-primary" id="button_store" type="submit">
                                    <span>Cadastrar</span>
                                </button>
                                <button style="display: none" class="btn btn-white"  id="button_create"  type="button">
                                    <span>Novo</span>
                                </button>
                                <button style="display: none" class="btn btn-primary" id="button_update" type="submit">
                                    <span>Atualizar</span>
                                </button>
                            </div>
                        </form>
                        <div id="errors" class="bg-red-50 p-4 border mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="model_form_phone" class="flex phone" style="display: none">
            <div class=" p-2 w-full">
                <label>Descrição</label>
                <x-text-input
                    class="block mt-1 w-full description"
                    type="text"
                    name="description[]"
                    placeholder="ex. Residência"
                />
            </div>
            <div class="p-2 w-full">
                <label>Telefone</label>
                <x-text-input
                    class="block mt-1 w-full"
                    type="text"
                    name="phone[]"
                />
            </div>
            <div class="align-end p-2">
                <button class="button_remove_phone btn btn-danger" type="button">
                    Deletar
                </button>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <table class="table border" id="table_users">
                            <thead>
                                <th class="">id</th>
                                <th class="">Nome</th>
                                <th class="">Email</th>
                                <th class="">telefones</th>
                                <th class="">Ações</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div id="navigation_table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    function responseCreateOrUpdateUser(){
        searchUsers()
        clearForm();
        addNewPhone();
    }

    function addNewPhone(phone = null){
        let newFormPhone = $('#model_form_phone').clone().attr('id', null).show()
        if(phone){
            $(newFormPhone).find('[name="description[]"]').val(phone.description)
            newFormPhone.find('[name="phone[]"]').val(phone.phone)
        }
        $('#forms_phones').append(newFormPhone);
    }

    function searchUsers(link = null){
        let route = link ?? "{{route('users.search')}}";
        $.get(route,{}, responseSearchUsers);
    }

    function responseSearchUsers(response){
        createRowsTableUser(response.data);
        creatNavigationTableUser(response.links);
    }

    function createRowsTableUser(users){
        let tbody = $('#table_users').find('tbody').empty();
        for (let user of users) {
            let row = $('<tr>');
            row.append($('<td>').text(user.id));
            row.append($('<td>').text(user.name));
            row.append($('<td>').text(user.email));
            row.append($('<td>').html('<button type="button" class="btn btn-white button_show_phones" data-id="'+user.id+'">Ver Telefones</button>'));
            row.append($('<td>').html('<button type="button" class="btn btn-white button_edit" data-id="'+user.id+'">Editar</button>'
                +'<button type="button" class="btn btn-danger button_delete" data-id="'+user.id+'">Deletar</button>'
            ));
            tbody.append(row);

            if(user.phones){
                row = $('<tr class="bg-indigo-50 table_user_phones" data-id="'+user.id+'" >');
                row.hide();
                let phoneList = $('<div class="flex justify-center">');
                for(let phone of user.phones){
                    phoneList.append('<div class="p-3 ml-3 mr-3"><b>'+phone.description+':</b> '+phone.phone+'</div>');
                }
                row.append($('<td colspan="5">').html(phoneList));
                tbody.append(row);
            }

        }
    }

    function creatNavigationTableUser(links){
        let navigation = $('#navigation_table').html('');
        for (let link of links) {
            if(link.url){
                let btnColor = link.active ? 'btn-primary' : 'btn-white' ;
                navigation.append('<button data-route="'+link.url+'" class="btn mr-1 ml-1 '+btnColor+' table_navigation_item">'
                                        +link.label
                                    +'</button>'
                );
            }
        }
    }

    function responseGetUser(user){
        clearForm();
        let form = $('#form_user');
        let phones = user.phones;
        delete user['phones']
        for(let index in user){
            if(form.find()){
                form.find('[name='+index+']').val(user[index])
            }
        }
        for(let phone of phones){
            addNewPhone(phone)
        }
        $('#button_store').hide();
        $('#button_update').show();
        $('#button_create').show();

    }

    function clearForm(){
        $('#form_user').trigger('reset');
        $('#forms_phones').html('');
        $('#button_store').show();
        $('#button_update').hide();
        $('#button_create').hide();
    }

    $(document).on('click','.button_remove_phone',function(){
        let formPhone = this.closest('.phone');
        formPhone.remove();
    })

    $('#form_user').submit(function(event){
        event.preventDefault();
        let id = $(this).find('[name="id"]').val();
        let _method = isNaN(parseInt(id)) ? 'POST' : 'PUT';
        let route = _method === 'PUT' ? 'user/'+id : 'user';
        let formData = new FormData(this)
        formData.append('_method', _method);
        let request = $.ajax({
            method:         'POST',
            data:           formData,
            url:            route,
            processData:    false,
            contentType:    false
        }).then(function(response){
           responseCreateOrUpdateUser(response)
        }).catch(function(response){
            if(response.status === 422){
                $('#errors').html('');
                let errors = response.responseJSON.errors;
                for(let index in errors){
                    $('#errors').append('<span class="p-2">'+errors[index]+'</span>');
                }
            }else{
                alert('Erro inesperado por favor contact o Administrador')
            }
        });
    })

    $(document).on('click','.button_delete', function(){
        let id = $(this).attr('data-id');
        if(confirm('Deseja realmente deleter este usuário?')){
            let request = $.ajax({
                method: 'DELETE',
                data: id,
                url:'user/'+id,
            }).then(function(response){
                searchUsers();
            }).catch(function(response){
                alert('Houve um erro ao deletar o usuário')
            });
        }
    })

    $(document).on('click','.button_edit',function(){
        let id = $(this).attr('data-id');
        $.get('user/'+id,{}, responseGetUser);
    });

    $(document).on('click','.table_navigation_item',function(){
        let route = $(this).attr('data-route');
        searchUsers(route)
    })

    $(document).on('click','.table_navigation_item',function(){
        let route = $(this).attr('data-route');
        searchUsers(route)
    })

    $('#button_add_phone').click(function(){
        addNewPhone();
    });

    $(document).on('click', '.button_show_phones',function(){
        let userId = $(this).attr('data-id');
        $('#table_users .table_user_phones[data-id='+userId+']').toggle();
    })

    $(document).on('click','#button_create',function(){
        clearForm();
    });

    addNewPhone();
    searchUsers();

</script>
@endsection
