@extends('layouts.default')
@section('content')
<div class="form">
    <form action="/" method="post" enctype="multipart/form-data">
        <label for="file"><h3>Wybierz plik do importu</h3></label>
        <input type="file" id="input_file" accept=".xml">
        <input type="submit" value="Wyslij" name="submit" id="submit_btn" disabled>
   </form>
   <textarea id="json_text" cols=100 rows=20 style="display: none" disabled>

   </textarea>
</div>
<script>
       function jsonFormat() {
            var badJSON = document.getElementById('json_text').value;
            var parseJSON = JSON.parse(badJSON);
            var JSONInPrettyFormat = JSON.stringify(parseJSON, undefined, 4);
            document.getElementById('json_text').value = JSONInPrettyFormat;
   }
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
                console.log(nodeName);
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

        const file = input_file.files[0]
        //console.log(file);

        let reader = new FileReader()
        reader.onload = function(e)
        {
            //console.log(e.target.result); 
            let xml2text = e.target.result
            var parser = new DOMParser();
            var xml = parser.parseFromString(xml2text, "text/xml");
            const json = xmlToJson(xml)

            let json2 = JSON.stringify(json);
            let jsonArea = document.getElementById("json_text")
            jsonArea.innerHTML = json2
            jsonArea.style.display = "block"
            jsonFormat()
            document.getElementById("submit_btn").removeAttribute('disabled');
            //console.log("3", json2);
        };
        reader.readAsText(file);
    });
</script>
@stop