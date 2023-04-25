<div>
    <legend>Карта</legend>
    <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
    <style>
        #YMapsID {
            margin: 20px 0;
            height: 500px;
            width: 100%;
        }
    </style>

    <script type="text/javascript">

        var myMap, myPlacemark, coords;

        ymaps.ready(init);

        function init () {
            //Определяем начальные параметры карты
            myMap = new ymaps.Map('YMapsID', {
                center: [<?=$model->{$lat};?>, <?=$model->{$long};?>],
                zoom: 17
            });

            //Определяем элемент управления поиск по карте
            var SearchControl = new ymaps.control.SearchControl({noPlacemark:true});

            //Добавляем элементы управления на карту
            myMap.controls
                .add(SearchControl)
                .add('zoomControl')
                .add('typeSelector')
                .add('mapTools');

            coords = [<?=$model->{$lat};?>, <?=$model->{$long};?>];

            //Определяем метку и добавляем ее на карту
            myPlacemark = new ymaps.Placemark([<?=$model->{$lat};?>, <?=$model->{$long};?>],{}, {preset: "twirl#redIcon", draggable: true});

            myMap.geoObjects.add(myPlacemark);

            //Отслеживаем событие перемещения метки
            myPlacemark.events.add("dragend", function (e) {
                coords = this.geometry.getCoordinates();
                savecoordinats();
            }, myPlacemark);

            //Отслеживаем событие щелчка по карте
            myMap.events.add('click', function (e) {
                coords = e.get('coordPosition');
                savecoordinats();
            });

            //Отслеживаем событие выбора результата поиска
            SearchControl.events.add("resultselect", function (e) {
                coords = SearchControl.getResultsArray()[0].geometry.getCoordinates();
                savecoordinats();
            });

            //Ослеживаем событие изменения области просмотра карты - масштаб и центр карты
            myMap.events.add('boundschange', function (event) {
                if (event.get('newZoom') != event.get('oldZoom')) {
                    savecoordinats();
                }
                if (event.get('newCenter') != event.get('oldCenter')) {
                    savecoordinats();
                }

            });

        }

        //Функция для передачи полученных значений в форму
        function savecoordinats (){
            var new_coords = [coords[0].toFixed(4), coords[1].toFixed(4)];
            myPlacemark.getOverlay().getData().geometry.setCoordinates(new_coords);


            document.getElementById("<?=$lat;?>").value = coords[0].toFixed(4);
            document.getElementById("<?=$long;?>").value = coords[1].toFixed(4);
        }

    </script>

    <div id="YMapsID"></div>
    <div id="coord_form">
        <?= $form->field($model, $lat)->textInput(["id" => $lat]) ?>
        <?= $form->field($model, $long)->textInput(["id" => $long]) ?>
    </div>
</div>

<hr>