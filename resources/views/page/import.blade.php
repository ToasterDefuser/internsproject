@extends('layouts.default')
@section('content')
@if(Session::has('alert'))
    <script>
        window.alert('{{ Session::get('alert')}}');
    </script>
@endif
<div class="form">
    <form action="/xml" method="post" enctype="multipart/form-data">
        @csrf
        <label for="file"><h3>Wybierz plik do importu</h3></label>
        <label for="input_file" class="btn-import" id="label_input_file">
            Dodaj plik
        </label>
        <input type="file" id="input_file" accept=".xml" name="xml_file" class="btn-import">
        <input type="submit" value="Zapisz" name="submit" id="submit_btn" class="btn-submit" disabled>
   </form>
   <div id="textAreaDiv"></div>
    
   </textarea>
</div>
<script>
    // funkcja formatująca plik JSOn
       function jsonFormat() {
            var badJSON = document.getElementById('json_text').value;
            var parseJSON = JSON.parse(badJSON);
            var JSONInPrettyFormat = JSON.stringify(parseJSON, undefined, 4);
            document.getElementById('json_text').value = JSONInPrettyFormat;
   }
   // funkcja konwertująca plik XML na JSON
    function xmlToJson(xml) {
   
            // Create the return object
        var obj = {};
        if (xml.nodeType == 1) { // element
            // do attributes
            if (xml.attributes.length > 0) {
            obj["@attributes"] = {};
                for (var j = 0; j < xml.attributes.length; j++) {
                    var attribute = xml.attributes.item(j);
                    obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                }
            }
        } else if (xml.nodeType == 3) { // text
            if(xml.nodeValue.startsWith('\n')) return
            
            obj = xml.nodeValue;
        }
        // do children
        if (xml.hasChildNodes()) {
            for(var i = 0; i < xml.childNodes.length; i++) {
                var item = xml.childNodes.item(i);
                var nodeName = item.nodeName;
                if(nodeName === "#text"){
                    nodeName = "value"
                }
                if (typeof(obj[nodeName]) == "undefined") {
                    obj[nodeName] = xmlToJson(item);
                } else {
                    if (typeof(obj[nodeName].push) == "undefined") {
                    var old = obj[nodeName];
                    obj[nodeName] = [];
                    obj[nodeName].push(old);
                    }
                    obj[nodeName].push(xmlToJson(item));
                }
            }
        }
        return obj;
    };
    
    const input_file = document.getElementById("input_file");
    input_file.addEventListener("change", () => {
        //<textarea id="json_text" cols=100 rows=20 style="display: none" disabled>

        let file = input_file.files[0];

        // zmiana wartości przyciska
        document.getElementById("label_input_file").innerHTML = file.name

        let reader = new FileReader()
        reader.onload = function(e)
        { 
            let xml2text = e.target.result
            let parser = new DOMParser();
            let xml = parser.parseFromString(xml2text, "text/xml");
            let json = xmlToJson(xml)
            
            let json2 = JSON.stringify(json);
            
            const parentElement = document.querySelector('#textAreaDiv')
            const existingTextArea = document.querySelector("#json_text")
            if(existingTextArea){
                parentElement.removeChild(existingTextArea)
            }
            const textArea = document.createElement('textarea');
            textArea.cols = 100;
            textArea.rows = 20;
            textArea.disabled = true
            textArea.id = 'json_text';
            textArea.value = json2;
            parentElement.appendChild(textArea);
            jsonFormat()
            document.getElementById("submit_btn").removeAttribute('disabled');
        };
        reader.readAsText(file);
    });
</script>
@stop