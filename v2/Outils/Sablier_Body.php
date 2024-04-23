	 <div id="overlay">
	  <div id="overlay-content">
	  	<table>
	  		<tr>
				    <td><img style="border: none;" src="../../Images/sablier.gif" /></td>
	    			<td><h1>Chargement en cours ...</h1></td>
	    	</tr>
	    </table>
	  </div>
	</div>
  
  <script>
    // tell the embed parent frame the height of the content
    if (window.parent && window.parent.parent){
      window.parent.parent.postMessage(["resultsFrame", {
        height: document.body.getBoundingClientRect().height,
        slug: "5y9zn6p5"
      }], "*")
    }
  </script>
  