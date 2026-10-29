@php
    $editorId = $field ?? 'description';
@endphp

<style>
    .editor-toolbar{
        border:1px solid #ddd;
        padding:10px;
        background:#f8f9fa;
        margin-bottom:5px;
        border-radius:6px;
    }
    .editor-toolbar button, .editor-toolbar select{
        border:1px solid #ccc;
        background:white;
        padding:5px 10px;
        margin-left:5px;
        cursor:pointer;
        border-radius:4px;
    }
    .editor-box{
        border:1px solid #ddd;
        min-height:260px;
        padding:15px;
        background:white;
        border-radius:6px;
        overflow:auto;
    }
</style>

<div class="editor-toolbar">

    <button type="button" onclick="format('bold')"><b>B</b></button>
    <button type="button" onclick="format('italic')"><i>I</i></button>
    <button type="button" onclick="format('underline')"><u>U</u></button>

    <select onchange="fontSize(this.value)">
        <option value="">سایز فونت</option>
        <option value="1">کوچک</option>
        <option value="3">معمولی</option>
        <option value="5">بزرگ</option>
        <option value="7">خیلی بزرگ</option>
    </select>

    <button type="button" onclick="format('insertUnorderedList')">• لیست</button>

    <button type="button" onclick="addLink()">لینک</button>

</div>

<div id="editor_{{ $editorId }}"
     contenteditable="true"
     class="editor-box">{!! $value !!}</div>


<textarea name="{{ $editorId }}"
          id="{{ $editorId }}"
          hidden></textarea>


<script>
    function format(cmd){
        document.execCommand(cmd,false,null);
    }

    function fontSize(size){
        document.execCommand("fontSize",false,size);
    }

    function addLink(){
        let url = prompt("آدرس لینک را وارد کنید:");
        if(url){
            document.execCommand("createLink",false,url);
        }
    }

    // انتقال متن ادیتور به textarea
    document.querySelector("form").addEventListener("submit", function () {

        document.getElementById("{{ $editorId }}").value =
            document.getElementById("editor_{{ $editorId }}").innerHTML;

    });
</script>

