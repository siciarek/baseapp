(function () {
    CKEDITOR.dialog.add('youtube',
        function (editor) {
            return{title: editor.lang.youtube.title, minWidth: CKEDITOR.env.ie && CKEDITOR.env.quirks ? 368 : 350, minHeight: 240,
                onShow: function () {
                    this.getContentElement('general', 'content').getInputElement().setValue('')
                },
                onOk: function () {
                    val = this.getContentElement('general', 'content').getInputElement().getValue();
                    src = 'http://www.youtube.com/embed/' + val.replace(/^.*?watch\?v\=(\w+).*$/, "$1") + '?rel=0';
                    var text =
                            + '<iframe '
                                + 'class="youtube-player" '
                                + 'title="YouTube video player" '
                                + 'type="text/html" '
                                + 'width="420" '
                                + 'height="351" '
                                + 'src="' + src + '" '
                                + 'frameborder="0">'
                                + '</iframe>'
                        ;

                    this.getParentEditor().insertHtml(text)
                },
                contents: [
                    {
                        label: editor.lang.common.generalTab,
                        id: 'general',
                        elements: [
                            {
                                type: 'html',
                                id: 'pasteMsg',
                                html: '<div style="white-space:normal;width:500px;">'
                                    + '<img style="margin:5px auto;" '
                                    + 'src="' + CKEDITOR.getUrl(CKEDITOR.plugins.getPath('youtube') + 'images/youtube_large.png')
                                    + '"><br />' + editor.lang.youtube.pasteMsg
                                    + '</div>'
                            },
                            {
                                type: 'html',
                                id: 'content',
                                style: 'width:340px;height:90px',
                                html: '<input size="200" style="border:1px solid black;background:white">',
                                focus: function () {
                                    this.getElement().focus()
                                }
                            }
                        ]
                    }
                ]
            }
        })
})();
