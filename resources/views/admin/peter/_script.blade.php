<script>
    $('select[name=region]').select2();
    $('select[name=zip_code]').select2();

    $('input[name=phone]').inputmask('9999999999');
    $('input[name=ine]').inputmask('AAAAAA99999999A999');

    $('input[name=outer_zip_code]').inputmask('99999');

    $('#anotherZipCode').change(function() {
        if ( this.checked ) {
            
            $('select[name=zip_code]').val('').trigger('change');

            $('#outerZipCode').show();
            $('#suburb').show();
            $('#zipCode').hide();
            $('#suburbId').hide();

            $('input[name=suburb]').attr('required', true);
            $('input[name=outer_zip_code]').attr('required', true);
            $('select[name=zip_code]').removeAttr('required');
            $('select[name=suburb_id]').removeAttr('required');

        }
        else {

            $('#outerZipCode').hide();
            $('#suburb').hide();
            $('#zipCode').show();
            $('#suburbId').show();

            $('input[name=suburb]').val('');
            $('input[name=outer_zip_code]').val(''); 

            $('input[name=outer_zip_code]').removeAttr('required');
            $('input[name=suburb]').removeAttr('required');
            $('select[name=zip_code]').attr('required', true);
            $('select[name=zip_code]').attr('required', true);


        }
    });


    $('select[name=zip_code]').on('change', function () {
        let zipCode = $(this).val();
        let _token = $token.val();
        $loading.show();

        $.post('/people/get-suburbs-options', { '_token':_token, 'zip_code':zipCode }, function(data) {
            $('select[name=suburb_id]').html(data);
            $loading.hide();
        });
    });

    function setName() {
        var firstName = document.getElementById('firstName').value;
        var lastName1 = document.getElementById('lastName1').value;
        var lastName2 = document.getElementById('lastName2').value;
        var name = firstName.trim() + ' ' + lastName1.trim() + ' ' + lastName2.trim();
        document.getElementById('name').value = name;
    }

    document.getElementById('personForm').addEventListener('submit', function(event) {
            
        event.preventDefault(); 

        let section = $('select[name=section]').val();
        let _token = $token.val();
        $loading.show();

        if ( !validateForm() ) {
            setInputErrorKeyup();
        } else {

            // Registrar submit
            localStorage.setItem('storePeter', 1);

            setName();
            $('#personForm').submit();
            $loading.show();
        }

    }); 

    function validateForm() {

        var phoneInput = $('input[name=phone]');
        var ineInput = $('input[name=ine]');
        var latInput = $('input[name=lat]');
        var lngInput = $('input[name=lng]');
        
        return validateINE(ineInput) && validatePhone(phoneInput) && 
            validateImages() &&
            validateRequired(latInput) && validateRequired(lngInput);    
    }

    function validateImages() {
        var imgHtml = $('.image-c .dropify-render').html();
        var ineHtml = $('.ine-c .dropify-render').html();

        if ( imgHtml === '' || ineHtml === '' ) {
            showWarningModal('Carga ambas imágenes para continuar.');
            return false;
        }

        return true;
    }

</script>