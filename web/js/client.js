/**
 * Created by jonni on 27.01.18.
 */
$(document).ready(function () {
    var client_info = new ClientInfo();
    var address_info = new ClientAddressInfo();
});

ClientInfo = function () {
    this.block = $('#main_info');
    this.edit_button = $('button[name="edit"]', this.block);
    this.save_button = $('button[name="save"]', this.block);
    this.cancel_button = $('button[name="cancel"]', this.block);
    this.edit_button.on('click', $.proxy(this.edit, this));
    this.save_button.on('click', $.proxy(this.save, this));
    this.cancel_button.on('click', $.proxy(this.cancel, this));
};

ClientInfo.prototype = {
    constructor: ClientInfo,

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
        $('input[name="born_date"]', this.block).val(client_info.born_date);
        $('input[name="sex"]', this.block).removeAttr('checked');
        $('input[name="sex"][value="' + client_info.db_sex + '"]', this.block).attr('checked', true);
    },

    cancel: function () {
        this.edit_button.removeClass('hide');
        this.save_button.addClass('hide');
        this.cancel_button.addClass('hide');
        $('p', this.block).removeClass('hide');
        $('input', this.block).addClass('hide');
        $('.input-group', this.block).addClass('hide');
    },

    save: function () {

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