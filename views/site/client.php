
<div class="col-lg-12" id="client_card">
    <h2>Карточка клиента</h2>
    <div class="col-lg-6" id="main_info">
        <h3>Общая информация</h3>
        <p>Фамилия <?=$client->last_name ?></p>
        <p>Имя <?=$client->first_name ?></p>
        <p>Пол <?=$client->sex ?></p>
        <p>Дата рождения <?=$client->born_date ?></p>
        <p>Телефон <?=$client->phone ?></p>
    </div>
    <div class="col-lg-6" id="address_info">
        <h3>Адреса</h3>
    </div>
</div>
