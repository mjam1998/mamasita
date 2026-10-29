<textarea id="content" name="description" class="form-control">{{ old('content', $value ?? '') }}</textarea>

<script src="{{ asset('tinymce/tinymce.js') }}"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'image link table code lists directionality advlist autolink charmap preview anchor searchreplace visualblocks fullscreen insertdatetime media wordcount',
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | removeformat code | ltr rtl',
        directionality: 'rtl',
        language: 'fa',
        height: 500,
        menubar: true,
        branding: false,

        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        // تنظیمات آپلود تصویر
        images_upload_url: '{{ route("admin.upload.image") }}',
        automatic_uploads: true,
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ route("admin.upload.image") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.upload.onprogress = function(e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function() {
                    if (xhr.status === 403) {
                        reject('خطای دسترسی: ' + xhr.status);
                        return;
                    }

                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('خطا در آپلود: ' + xhr.status);
                        return;
                    }

                    var json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        reject('خطا: پاسخ نامعتبر از سرور');
                        return;
                    }

                    resolve(json.location);
                };

                xhr.onerror = function() {
                    reject('خطا در ارتباط با سرور');
                };

                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            });
        },

        // تنظیمات جدول
        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        table_appearance_options: true,
        table_grid: true,
        table_resize_bars: true,

        content_style: 'body { font-family: Vazir, Tahoma, Arial; font-size: 14px; direction: rtl; }'
    });
</script>
