<script>
    var ineInput = document.getElementById('ine');

    ineInput.addEventListener('focusout', function(event) {
        $loading.show();

        let _token = $token.val();
        let ine = $('input[name=ine]').val();

        $.post('/voters/get-info', {
            '_token': _token,
            'ine': ine
        }, function(data) {
            console.log('Datos: ', data);
            if (data.success) {
                $('input[name=first_name]').val(data.nombre);
                $('input[name=last_name_1]').val(data.paterno);
                $('input[name=last_name_2]').val(data.materno);
                $('input[name=curp]').val(data.curp);
                $('input[name=street]').val(data.calle);
                $('input[name=ext_number]').val(data.ext);
                $('input[name=int_number]').val(data.int);
                $('input[name=lote]').val(data.lote);
                //$('input[name=mza]').val(data.mza);
                $('select[name="section"]').val(data.seccion).change();
                $('select[name="zip_code"]').val(data.cp).change();

            }

            $loading.hide();
        });

    });

    document.getElementById('buscarClaveBtn').addEventListener('click', function() {
        $loading.show();

        let _token = $token.val();
        let firstName = $('input[name=first_name_search]').val();
        let lastName1 = $('input[name=last_name_1_search]').val();
        let lastName2 = $('input[name=last_name_2_search]').val();
        let street = $('input[name=street_search]').val();

        $.post('/voters/get-info', {
            '_token': _token,
            'firstName': firstName,
            'lastName1': lastName1,
            'lastName2': lastName2,
            'street': street
        }, function(data) {
            console.log('Datos: ', data);
            if (data.success) {
                $('input[name=first_name]').val(data.nombre);
                $('input[name=last_name_1]').val(data.paterno);
                $('input[name=last_name_2]').val(data.materno);
                $('input[name=curp]').val(data.curp);
                $('input[name=street]').val(data.calle);
                $('input[name=ine]').val(data.cve);
                $('input[name=ext_number]').val(data.ext);
                $('input[name=int_number]').val(data.int);
                $('input[name=lote]').val(data.lote);
                //$('input[name=mza]').val(data.mza);
                $('select[name="section"]').val(data.seccion).change();
                $('select[name="zip_code"]').val(data.cp).change();
                $('input[name=first_name_search]').val('');
                $('input[name=last_name_1_search]').val('');
                $('input[name=last_name_2_search]').val('');
                $('input[name=street_search]').val('');
            } else {
                showWarningModal(data.error);
            }
            $loading.hide();
            $('#searchModal').modal('hide');
        });

    });




    $('select[name=zip_code]').select2();

    $('input[name=phone]').inputmask('9999999999');

    $('input[name=mobile]').inputmask('9999999999');

    $('input[name=ine]').inputmask('AAAAAA99999999A999');

    $('select[name=zip_code]').on('change', function() {
        let zipCode = $(this).val();
        let _token = $token.val();
        $loading.show();

        $.post('/people/get-suburbs-options', {
            '_token': _token,
            'zip_code': zipCode
        }, function(data) {
            $('select[name=suburb_id]').html(data);
            $loading.hide();
        });
    });


    $('select[name=vote]').on('change', function() {

        // Limpia el campo en localstorage
        localStorage.setItem('vote', '');

        var vote = $(this).val();

        if (vote != null) {

            $loading.show();

            let _token = $token.val();

            $.post('/promoted/get-form', {
                '_token': _token,
                'vote': vote
            }, function(data) {

                $('.vote-form').html(data);

                if (vote == 'VP' || vote == 'VA') {
                    // Eventos VP y VA
                    $('select[name=person_id]').select2();
                    $('select[name=section]').select2();

                    setMobEvent();
                    setBlockEvent();
                    setSectionEvent();

                    //setSection();
                    setPersonId();

                    $loading.hide();

                } else {


                    $.post('/promoted/get-structure', {
                        '_token': _token,
                    }, function(data) {

                        $('.structure').html(data);

                        // Eventos VC y VAE
                        $('select[name=section]').select2();
                        $('select[name=corporation_id]').select2();
                        $('select[name=corporation_person_id]').select2();

                        setCorporationEvent();
                        setSectionEvent();

                        setSection();
                        setCorporationId();

                        $loading.hide();

                    });

                }



            });

        } else {
            $('.vote-form').html('');
            $('.structure').html('');
        }
    });

    function levenshtein(a, b) {
        if (a.length === 0) return b.length;
        if (b.length === 0) return a.length;

        var matrix = [];

        // Incremento de la primera columna y la primera fila
        for (var i = 0; i <= b.length; i++) {
            matrix[i] = [i];
        }
        for (var j = 0; j <= a.length; j++) {
            matrix[0][j] = j;
        }

        // Rellena la matriz
        for (var i = 1; i <= b.length; i++) {
            for (var j = 1; j <= a.length; j++) {
                if (b.charAt(i - 1) == a.charAt(j - 1)) {
                    matrix[i][j] = matrix[i - 1][j - 1];
                } else {
                    matrix[i][j] = Math.min(matrix[i - 1][j - 1] + 1, // sustitución
                        Math.min(matrix[i][j - 1] + 1, // inserción
                            matrix[i - 1][j] + 1)); // eliminación
                }
            }
        }

        return matrix[b.length][a.length];
    }

    // Función para encontrar la opción más similar
    function findClosestOption(value, options) {
        var minDistance = Infinity;
        var closestOption = null;
        options.each(function() {
            var optionText = $(this).text();
            console.log(optionText)
            var distance = levenshtein(value, optionText);
            if (distance < minDistance) {
                minDistance = distance;
                closestOption = $(this);
            }
        })
        return closestOption;
    }


    function setPersonId() {
        let personId = localStorage.getItem('personId');

        if (personId != null && personId != undefined && personId != '') {
            localStorage.setItem('personId', '');
            $('select[name=person_id]').val(personId).trigger('change');
        }
    }

    function setBlock() {
        let block = localStorage.getItem('block');

        if (block != null && block != 'null' && block != undefined && block != '') {
            localStorage.setItem('block', '');
            $('select[name=block]').val(block).trigger('change');
        }
    }

    function setSection() {
        let section = localStorage.getItem('section');

        if (section != null && section != undefined && section != '') {
            localStorage.setItem('section', '');
            $('select[name=section]').val(section).trigger('change');
        }
    }

    function setCorporationId() {
        let corporationId = localStorage.getItem('corporationId');

        if (corporationId != null && corporationId != undefined && corporationId != '') {
            localStorage.setItem('corporationId', '');
            $('select[name=corporation_id]').val(corporationId).trigger('change');
        }
    }

    function setBlockEvent() {
        $('select[name=block]').on('change', function() {
            var mr = $('select[name=block] option:selected').attr('mr');
            $('input[name=mr]').val(mr);
        });
    }


    function setMobEvent() {
        let _token = $token.val();

        $('select[name=person_id]').on('change', function() {
            var section = $('select[name=person_id] option:selected').attr('section');
            $('select[name=section]').val(section).trigger('change');

            var img = $('select[name=person_id] option:selected').attr('img');
            $('.img-c').html('<img src="' + img + '" class="img-fluid" />');

            var personId = $(this).val();
            getPromotedNumber(personId);

        });
    }

    function getPromotedNumber(personId) {
        let _token = $token.val();

        $.post('/promoted/get-promoted-number', {
            '_token': _token,
            'id': personId,
        }, function(response) {

            $('input[name=promotedNumber]').val(response.promotedNumber);
            $('#week1').val(response.week1);
            $('#week2').val(response.week2);
            $('#week3').val(response.week3);
            $('#week4').val(response.week4);
            $('#week5').val(response.week5);

        });
    }


    function setSectionEvent() {
        $('select[name=section]').on('change', function() {
            let section = $(this).val();
            getSectionInfo(section);
        });
    }

    function setCorporationEvent() {
        $('select[name=corporation_id]').on('change', function() {
            let corporationId = $(this).val();
            getCorporationPeople(corporationId);
        });
    }

    function getCorporationPeople(corporationId) {
        let _token = $token.val();
        $loading.show();

        $.post('/corporations/people/get-options', {
            '_token': _token,
            'corporation_id': corporationId
        }, function(data) {
            $('select[name=corporation_person_id]').html(data);
            $loading.hide();

            setCorporationPersonId();
        });
    }

    function setCorporationPersonId() {
        let corporationPersonId = localStorage.getItem('corporationPersonId');

        if (corporationPersonId != null && corporationPersonId != undefined && corporationPersonId != '') {
            localStorage.setItem('corporationPersonId', '');
            $('select[name=corporation_person_id]').val(corporationPersonId).trigger('change');
        }
    }

    function getSectionInfo(section) {
        let _token = $token.val();
        $loading.show();

        $.post('/promoted/get-section-info', {
            '_token': _token,
            'section': section
        }, function(response) {
            setSectionInfo(response.section);
            setBlocksOptions(response.blocks);

            setBlock();

            $loading.hide();
        });
    }

    function setSectionInfo(section) {

        if (section == null) {

            $('input[name=mr]').val('');
            $('input[name=df]').val('');
            $('input[name=dl]').val('');
            $('input[name=region]').val('');
            $('input[name=zone]').val('');
            $('input[name=dependence]').val('');

        } else {
            $('input[name=mr]').val('');
            $('input[name=df]').val(section.df);
            $('input[name=dl]').val(section.dl);
            $('input[name=region]').val(section.region);
            $('input[name=zone]').val(section.zone);
            $('input[name=dependence]').val(section.dependence);
            //$('input[name=promotedNumber]').val(section.promotedNumber);
        }

    }

    function setBlocksOptions(blocks) {
        var htmlOptions = '<option selected disabled value="">Selecciona una opción</option>';

        $.each(blocks, function(index, block) {
            htmlOptions += '<option mr="' + block.mr + '" value="' + block.block + '">' + block.block +
                '</option>';
        });

        $('select[name=block]').html(htmlOptions);

    }

    function setAddress() {
        var geocoder = new google.maps.Geocoder();
        let address = getFullAddress();

        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status === 'OK') {
                var location = results[0].geometry.location;
                $('input[name=lat]').val(location.lat());
                $('input[name=lng]').val(location.lng());

            } else {
                $('input[name=lat]').val('');
                $('input[name=lng]').val('');
            }
        });
    }

    function validateImages() {
        var imgHtml = $('.image-c .dropify-render').html();
        var ineHtml = $('.ine-c .dropify-render').html();

        if (imgHtml === '' || ineHtml === '') {
            showWarningModal('Carga el INE para continuar.');
            return false;
        }

        return true;
    }

    function setName() {
        var firstName = document.getElementById('firstName').value;
        var lastName1 = document.getElementById('lastName1').value;
        var lastName2 = document.getElementById('lastName2').value;
        var name = firstName.trim() + ' ' + lastName1.trim() + ' ' + lastName2.trim();
        document.getElementById('name').value = name;
    }

    function validateForm() {
        var phoneInput = $('input[name=phone]');
        var mobileInput = $('input[name=mobile]');
        var ineInput = $('input[name=ine]');

        return validateINE(ineInput) &&
            validatePhone(phoneInput) && validatePhone(mobileInput);
    }

    function saveInLocalStorage() {

        localStorage.setItem('storeProm', 1);

        var vote = $('select[name=vote]').val();
        var personId = $('select[name=person_id]').val();
        var block = $('select[name=block]').val();
        var section = $('select[name=section]').val();
        var corporationId = $('select[name=corporation_id]').val();
        var corporationPersonId = $('select[name=corporation_person_id]').val();

        localStorage.setItem('vote', vote);
        localStorage.setItem('personId', personId);
        localStorage.setItem('block', block);
        localStorage.setItem('section', section);
        localStorage.setItem('corporationId', corporationId);
        localStorage.setItem('corporationPersonId', corporationPersonId);

    }
</script>
