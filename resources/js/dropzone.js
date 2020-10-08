const { default: Axios } = require("axios");

document.addEventListener('DOMContentLoaded', () => {

    if (document.querySelector('#dropzone')) {

        Dropzone.autoDiscover = false;

        const dropzone = new Dropzone('div#dropzone', {
            url: '/imagenes/store',
            dictDefaultMessage: 'Arrastra aqui hasta 10 imágenes',
            maxFiles: 10,
            required: true,
            acceptedFiles: '.png,.jpg,.gif,.bmp,.jpeg',
            addRemoveLinks: true,
            dictRemoveFile: 'Eliminar Imagen',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            success: function(file, respuesta) {
                console.log(respuesta);
                file.nombreServidor = respuesta.archivo;
            },
            sending: function(file, xhr, formData) {
                formData.append('uuid', document.querySelector('#uuid').value);
            },
            removedfile: function(file, respuesta) {
                console.log(file);

                const params = {
                    imagen: file.nombreServidor
                }

                axios.post('/imagenes/destroy', params)
                    .then(respuesta => {
                        console.log(respuesta);
                    });

                // Elimino del DOM
                file.previewElement.parentNode.removeChild(file.previewElement);
            }
        });

    }



});
