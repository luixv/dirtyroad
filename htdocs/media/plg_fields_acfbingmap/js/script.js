function acf_callback_bingmaps(){for(var a=document.querySelectorAll(".acf_bingmap"),t=0;t<a.length;t++){map=a[t];var e=parseFloat(map.getAttribute("data-latitude")),o=parseFloat(map.getAttribute("data-longitude")),p=parseInt(map.getAttribute("data-zoom")),r=map.getAttribute("id"),i=new Microsoft.Maps.Location(e,o);map=new Microsoft.Maps.Map("#"+r,{center:i,mapTypeId:Microsoft.Maps.MapTypeId.aerial,zoom:p});var n=new Microsoft.Maps.Pushpin(i,{color:"red"});map.entities.push(n)}}
