( function() {
    "'use strict';"
    var HOST = '/apps/collivery';

    //finds next item in form
    var findNext = function( parent, childSelector ) {
        return parent.closest( 'form' ).find( childSelector ).first();
    };

    var main = function( $ ) {

        function RequestCounter() {
            var count = 0;
            return {
                get: function() {
                    return count;
                },
                increment: function( step = 1 ) {
                    count += step;
                },
                decrement: function( step = 1 ) {
                    count -= step;
                },
                reset: function() {
                    count = 0;
                }
            }
        }

        var requestCounter = new RequestCounter();

        $( document ).on( 'ajaxSend', function( e, xhr ) {

            $( 'input,select' ).attr( 'disabled', 'disabled' );

            requestCounter.increment();

        } ).on( 'ajaxSuccess', function( e, xhr ) {

            requestCounter.decrement();

        } ).on( 'ajaxError', function() {
            $( 'body .server-error' ).remove();

            var errorBox = $( '<div/>' )
                .css( {
                    position: 'fixed',
                    top: 0,
                    left: 0,
                    width: $( window ).width(),
                    height: 'auto',
                    "text-align": 'center',
                    background: 'rgb(245, 195, 75) none repeat scroll 0% 0%',
                    "z-index": 100000
                } );

            $( window ).resize( function() {
                errorBox.css( {
                    width: $( window ).width()
                } );
            } );

            errorBox.addClass( 'server-error' );
            var errorLink = $( '<a/>' )
                .attr( 'href', window.location.href );
            errorLink.text( 'Network error. Reload page' );
            errorBox.append( errorLink );
            errorBox.append( errorLink )

            $( 'body' ).prepend( errorBox );
        } ).on( 'ajaxComplete', function( e, xhr ) {
            if ( requestCounter.get() == 0 ) {
                $( 'input,select' ).removeAttr( 'disabled' );
            }
        } );

        var dropdown = function( params ) {
            if ( !params ) {
                throw new Error( 'Invalid arguments' );
            }
            var dropdown = $( '<select/>' );

            dropdown.attr( 'name', params.name );
            dropdown.attr( 'id', params.id );

            //data to be attached to the element
            $.each( params.extras, function( k, v ) {
                dropdown.data( k, params.extras[ k ] );
            } );
            $.each( params.data, function( k, v ) {
                var option = $( '<option/>' );
                option.attr( 'value', k );
                option.text( v );

                if ( k == params.currentVal )
                    option.attr( 'selected', 'selected' );

                dropdown.append( option );
            } );

            return $( dropdown );
        };

        var provinceFields = $( '[name="address[province]"' );
        provinceFields.show();


        var getProvinces = function( success, fail ) {
            var provinces = [];

            provinceFields.first().find( 'option' ).each( function() {
                provinces.push( $( this ).val() );
            } );


            $.ajax( {
                url: HOST + '/script/towns',
                dataType: 'json',
                method: 'GET',
                data: {
                    provinces: provinces.join()
                },
                crossDomain: true,
                success: success
            } );
        };

        var getSuburbs = function( townId, success, error ) {
            $.ajax( {
                url: HOST + '/script/' + townId + '/suburbs',
                dataType: 'json',
                method: 'GET',
                success: success
            } );
        };


        var getLocationTypes = function( success, error ) {
            $.ajax( {
                url: HOST + '/scripts/location-types',
                method: 'GET',
                dataType: 'json',
                success: success,
                error: error,
            } );
        };


        var cityFields = $( '[name="address[city]"]' );
        // var cityField = $( 'address[city]' );

        var populateTowns = function( cityField, towns ) {
            cityField = $( cityField );
            var newCityField = dropdown( {
                data: towns,
                name: cityField.attr( 'name' ),
                id: cityField.attr( 'id' ),
                currentVal: cityField.val()
            } );

            cityField.replaceWith( newCityField );

            newCityField.on( 'change', function( e ) {
                var newCityField = $( this );
                var form = $( newCityField.parents( 'form' ).get( 0 ) );
                var suburbsField = $( form.find( '[name="address[zip]"]' ).get( 0 ) );

                getSuburbs( newCityField.val(), function( suburbs ) {
                    var newSuburbsField = dropdown( {
                        data: suburbs,
                        name: suburbsField.attr( 'name' ),
                        id: suburbsField.attr( 'id' ),
                        currentVal: suburbsField.val(),
                        extras: {
                            suburbs: suburbs
                        }
                    } );

                    suburbsField.replaceWith( newSuburbsField );
                }, function( suburbs ) {

                } );
            } );
            newCityField.change();
        };

        var addAddress = $( 'a[name="Shopify.CustomerAddress.toggleNewForm(); return false;"]' );
        addAddress.click();

        var country = $( 'select[name="address[country]"]' );
        country.val( 'South Africa' );
        country.change();


        getProvinces( function( towns ) {
            provinceFields.each( function() {
                var provinceField = $( this );

                if ( provinceField.val() in towns ) {
                    var townField = findNext( provinceField, '[name="address[city]"]' );
                    var provinceTowns = towns[ provinceField.val() ];

                    provinceField.data( 'towns', provinceTowns );
                    provinceField.find( 'option' ).each( function() {
                        var provinceOption = $( this );
                        provinceOption.data( 'towns', towns[ provinceOption.val() ] );
                    } );

                    provinceField.on( 'change', function() {
                        var currentTowns = $( this ).find( 'option:selected' ).data( 'towns' );
                        populateTowns( findNext( $( this ), '[name="address[city]"]' ), currentTowns );
                    } );

                    provinceField.change();
                }
            } );
        } );

        //rename zip 
        $( 'label[for^="AddressZip"]' ).text( 'Suburb' );
        var locationTypeFields = $( '[name="address[location_type]"' );

        //disable countries except ZA
        $( 'select[name="address[country]"]  option[value!="South Africa"]' ).attr( 'disabled', 'disabled' );

        // getLocationTypes( function( locationTypes ) {
        //     locationTypeFields.each( function( i, locationTypeField ) {
        //         $( locationTypeField ).replaceWith( dropdown( {
        //             data: locationTypes,
        //             name: $( locationTypeField ).attr( 'name' ),
        //             id: $( locationTypeField ).attr( 'id' ),
        //         } ) );
        //     } );
        // } );

    };

    var callback = function() {
        jQuery3 = jQuery.noConflict( true );
        main( jQuery3 );
    };

    //load for addresses page only
    if ( window.location.href.indexOf( '/addresses' ) !== -1 )
        if ( typeof jQuery == 'undefined' || parseFloat( jQuery.fn.jquery ) < 1.9 ) {
            var script = document.createElement( 'script' );
            script.type = 'text/javascript';

            if ( script.readyState ) {
                script.onreadystatechange = function() {
                    if ( script.readyState == 'loaded' || script.readyState == 'complete' ) {
                        script.onreadystatechange = null;
                        callback();
                    }
                };
            }
            else {
                script.onload = function() {
                    script.onload = null;
                    callback();
                };
            }

            script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js';
            document.getElementsByTagName( 'head' )[ 0 ].appendChild( script );

        }
        else {
            main( jQuery );
        }
} )();