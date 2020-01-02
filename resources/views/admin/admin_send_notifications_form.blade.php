@push('scripts')
    <script src="{{ asset('packages/sleepingowl/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('packages/sleepingowl/ckeditor/config.js') }}"></script>
@endpush

<div style="text-align: center">
    <h4 class="panel panel-default" style="border: 1px solid darkgrey; padding: 10px; display: none; background: #fcfcfc; text-align: center">
        <div style="display: inline-block"  id="show_info_message"></div><br>
        <img src="{{ asset('admin_custom/img/mail_loading_bar.gif') }}" id="mail_gif" style="display: none" width="110px" height="100px">
    </h4>
</div>

<div class="card">
    <div class="card-body">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="subject" class="control-label">
                    Тема
                </label>
                <input type="text" id="subject" name="subject" class="form-control">
            </div>
            <div class="form-group form-element-textarea">
                <label for="message_text" class="control-label">
                    Текст сообщения
                </label>
                <textarea id="message_text" name="message_text" class="form-control ckeditor"></textarea>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="emails_confirmed" checked>
                <label class="form-check-label" for="emails_confirmed">Отслылать пользователям только с подтвержденным Email</label>
            </div>
            <button class="btn-primary" id="send_start">Начать рассылку</button>
        </div>
    </div>
</div>

@push('footer-scripts')
    <script>
        let editor;
        CKEDITOR.on('instanceReady', function(ev)
        {
            editor = ev.editor;
        });

        function ckeditorReadOnly(isReadOnly)
        {
            editor.setReadOnly(isReadOnly);
        }
    </script>

    <script src="{{ asset('admin_custom/js/send_notifications.js') }}"></script>
@endpush
