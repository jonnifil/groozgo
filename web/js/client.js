/**
 * Created by jonni on 27.01.18.
 */
$(document).ready(function () {
    var client_info = new ClientInfo();
    var address_info = new ClientAddressInfo();
    client_info.add();
});

ClientInfo = function () {
    this.block = $('#main_info');
    this.edit_button = $('button[name="edit"]', this.block);
    this.save_button = $('button[name="save"]', this.block);
    this.cancel_button = $('button[name="cancel"]', this.block);
    this.error_view = $('#error_message');
    this.edit_button.on('click', $.proxy(this.edit, this));
    this.save_button.on('click', $.proxy(this.save, this));
    this.cancel_button.on('click', $.proxy(this.cancel, this));
};

ClientInfo.prototype = {
    constructor: ClientInfo,

    add: function () {
        if (client_edit == 0){
            this.edit();
        }

    },

    edit: function () {
        this.edit_button.addClass('hide');
        this.save_button.removeClass('hide');
        this.cancel_button.removeClass('hide');
        this.load_edit();
        $('p', this.block).addClass('hide');
        $('input', this.block).removeClass('hide');
        $('.input-group', this.block).removeClass('hide');
    },

    load_edit: function () {
        $('input[name="first_name"]', this.block).val(client_info.first_name);
        $('input[name="last_name"]', this.block).val(client_info.last_name);
        $('input[name="phone"]', this.block).val(client_info.db_phone);
        $('input[name="born_date"]', this.block).val(client_info.db_born_date);
        $('input[name="sex"]', this.block).removeAttr('checked');
        $('input[name="sex"][value="' + client_info.db_sex + '"]', this.block).attr('checked', true);
    },

    cancel: function () {
        this.error_view.empty();
        this.edit_button.removeClass('hide');
        this.save_button.addClass('hide');
        this.cancel_button.addClass('hide');
        $('p', this.block).removeClass('hide');
        $('input', this.block).addClass('hide');
        $('.input-group', this.block).addClass('hide');
        if (client_edit == 0){
            window.location.assign(back_url);
        }
    },

    save: function () {
        this.error_view.empty();
        var data = {}, errors, error;
        data.id = client_info.id;
        data.last_name = $('input[name="last_name"]', this.block).val().trim();
        data.first_name = $('input[name="first_name"]', this.block).val().trim();
        data.phone = $('input[name="phone"]', this.block).val().trim();
        data.born_date = $('input[name="born_date"]', this.block).val();
        data.sex = $('input[name="sex"]:checked', this.block).val();
        errors = this.validate(data);
        if (errors.length > 0){
            for (var i in errors){
                error = errors[i];
                $('<p class="text-danger">'+error+'</p>').appendTo(this.error_view);
            }
            return false;
        }else{
            var param = $('meta[name=csrf-param]').attr("content");
            var token = $('meta[name=csrf-token]').attr("content");
            var result = {client_data : data};
            result[param] = token;
            $.ajax({
                type: "POST",
                data: result,
                url: create_url,
                success: function (data) {
                    var url = client_url + '&id=' + data
                    window.location.assign(url);
                }
            })
        }
    },
    
    validate: function (data) {
        var errors = [];
        if (data.first_name.length == 0)
            errors.push('Поле Имя должно быть заполнено!');
        if (data.last_name.length == 0)
            errors.push('Поле Фамилия должно быть заполнено!');
        if (data.sex === undefined)
            errors.push('Укажите пол!');
        var date = new Date(data.born_date);
        if (date == 'Invalid Date')
            errors.push('Не валидная дата!');
        var reg = new RegExp('[0-9]{10}');
        if (data.phone.length != 10 || ! reg.test(data.phone))
            errors.push('Не валидный номер телефона!');
        return errors;
    }
};

ClientAddressInfo = function () {
    var $this = this;
    this.block = $('#address_info');
    this.address_dialog = new AddressDialog(this);
    $('[name="add_address"]', this.block).on('click', $.proxy(this.create, this));
    $('[name="edit_address"]', this.block).on('click', function (event) {
        $this.edit(event)
    });

};

ClientAddressInfo.prototype = {
    constructor: ClientAddressInfo,

    edit: function (event) {
        var id = $(event.currentTarget).attr('data_id');
        this.address_dialog.edit(id);
    },

    create: function () {
        this.address_dialog.add();
    }
};

AddressDialog = function (parent) {
    this.grid_block = parent;
    this.block = $('#address_dialog');
    $('[name="save"]', this.block).on('click', $.proxy(this.save, this));
};

AddressDialog.prototype = {
    constructor: AddressDialog,

    add: function () {
        var data = {
            id: 0,
            client_id: client_info.id,
            name: '',
            address: ''
        };
        this.show(data);
    },

    edit: function (id) {
        var $this = this;
        var param = $('meta[name=csrf-param]').attr("content");
        var token = $('meta[name=csrf-token]').attr("content");
        var data = {};
        data[param] = token;
        data.id = id;
        $.ajax({
            type: "POST",
            data: data,
            url: address_url,
            success: function (data) {
                $this.show($.parseJSON(data));
            }
        })
    },

    show: function (data) {
        var value;
        for (var name in data){
            value = data[name];
            console.log(name+'-'+value)
            $('input[name="'+name+'"]', this.block).val(value);
        }
        this.block.modal('show');
    },

    save: function () {
        var url = window.location.toString();
        var $this = this;
        var param = $('meta[name=csrf-param]').attr("content");
        var token = $('meta[name=csrf-token]').attr("content");
        var data = {};
        data[param] = token;
        data.client_address = {
            id: $('[name="id"]', this.block).val(),
            client_id: $('[name="client_id"]', this.block).val(),
            name: $('[name="name"]', this.block).val(),
            address: $('[name="address"]', this.block).val()
        };
        $.ajax({
            type: "POST",
            data: data,
            url: save_url,
            success: function (data) {
                window.location.assign(url);
            }
        });
    }
};

function onLoad (ymaps) {
    var suggestView = new ymaps.SuggestView('address_add');
}