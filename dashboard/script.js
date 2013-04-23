
/* Filedrop
*********************************************************************************************/

(function(b){jQuery.event.props.push("dataTransfer");var o={},u={url:"",refresh:1000,paramname:"userfile",maxfiles:25,maxfilesize:1,data:{},drop:j,dragEnter:j,dragOver:j,dragLeave:j,docEnter:j,docOver:j,docLeave:j,beforeEach:j,afterAll:j,rename:j,error:function(B,A,e){alert(B)},uploadStarted:j,uploadFinished:j,progressUpdated:j,speedUpdated:j},k=["BrowserNotSupported","TooManyFiles","FileTooLarge"],p,g=false,n=0,c;b.fn.filedrop=function(e){o=b.extend({},u,e);this.bind("drop",i).bind("dragenter",m).bind("dragover",x).bind("dragleave",y);b(document).bind("drop",h).bind("dragenter",l).bind("dragover",q).bind("dragleave",w)};function i(A){o.drop(A);c=A.dataTransfer.files;if(c===null||c===undefined){o.error(k[0]);return false}n=c.length;t();A.preventDefault();return false}function v(A,B,E){var C="--",D="\r\n",e="";b.each(o.data,function(F,G){if(typeof G==="function"){G=G()}e+=C;e+=E;e+=D;e+='Content-Disposition: form-data; name="'+F+'"';e+=D;e+=D;e+=G;e+=D});e+=C;e+=E;e+=D;e+='Content-Disposition: form-data; name="'+o.paramname+'"';e+='; filename="'+A+'"';e+=D;e+="Content-Type: application/octet-stream";e+=D;e+=D;e+=B;e+=D;e+=C;e+=E;e+=C;e+=D;return e}function d(F){if(F.lengthComputable){var B=Math.round((F.loaded*100)/F.total);if(this.currentProgress!=B){this.currentProgress=B;o.progressUpdated(this.index,this.file,this.currentProgress);var A=new Date().getTime();var D=A-this.currentStart;if(D>=o.refresh){var C=F.loaded-this.startData;var E=C/D;o.speedUpdated(this.index,this.file,E);this.startData=F.loaded;this.currentStart=A}}}}function t(){g=false;if(!c){o.error(k[0]);return false}var C=0,D=0;if(n>o.maxfiles){o.error(k[1]);return false}for(var B=0;B<n;B++){if(g){return false}try{if(a(c[B])!=false){if(B===n){return}var e=new FileReader(),A=1048576*o.maxfilesize;e.index=B;if(c[B].size>A){o.error(k[2],c[B],B);D++;continue}e.onloadend=F;e.readAsBinaryString(c[B])}else{D++}}catch(E){o.error(k[0]);return false}}function F(L){if(L.target.index==undefined){L.target.index=z(L.total)}var M=new XMLHttpRequest(),I=M.upload,J=c[L.target.index],H=L.target.index,K=new Date().getTime(),N="------multipartformboundary"+(new Date).getTime(),G;newName=f(J.name);if(typeof newName==="string"){G=v(newName,L.target.result,N)}else{G=v(J.name,L.target.result,N)}I.index=H;I.file=J;I.downloadStartTime=K;I.currentStart=K;I.currentProgress=0;I.startData=0;I.addEventListener("progress",d,false);M.open("POST",o.url,true);M.setRequestHeader("content-type","multipart/form-data; boundary="+N);M.sendAsBinary(G);o.uploadStarted(H,J,n);M.onload=function(){if(M.responseText){var P=new Date().getTime(),Q=P-K,O=o.uploadFinished(H,J,jQuery.parseJSON(M.responseText),Q);C++;if(C==n-D){r()}if(O===false){g=true}}}}}function z(A){for(var e=0;e<n;e++){if(c[e].size==A){return e}}return undefined}function f(e){return o.rename(e)}function a(e){return o.beforeEach(e)}function r(){return o.afterAll()}function m(A){clearTimeout(p);A.preventDefault();o.dragEnter(A)}function x(A){clearTimeout(p);A.preventDefault();o.docOver(A);o.dragOver(A)}function y(A){clearTimeout(p);o.dragLeave(A);A.stopPropagation()}function h(A){A.preventDefault();o.docLeave(A);return false}function l(A){clearTimeout(p);A.preventDefault();o.docEnter(A);return false}function q(A){clearTimeout(p);A.preventDefault();o.docOver(A);return false}function w(A){p=setTimeout(function(){o.docLeave(A)},200)}function j(){}try{if(XMLHttpRequest.prototype.sendAsBinary){return}XMLHttpRequest.prototype.sendAsBinary=function(A){function B(D){return D.charCodeAt(0)&255}var C=Array.prototype.map.call(A,B);var e=new Uint8Array(C);this.send(e.buffer)}}catch(s){}})(jQuery);

/* Filedrop Config
*********************************************************************************************/

$(function(){

	var dropbox = $('#dropbox'),
		message = $('.message', dropbox);

	dropbox.filedrop({
		// The name of the $_FILES entry:
		paramname:'file',

		maxfiles: 2,
    	maxfilesize: 2,
		url: 'publish.php',

		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
			setInterval("location.reload()", 3500);
		},

    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Your browser does not support HTML5 file uploads!');
					break;
				case 'TooManyFiles':
					alert('Too many files! Please select 2 at most!');
					break;
				case 'FileTooLarge':
					alert(file.name+' is too large! Please upload files up to 2mb (configurable).');
					break;
				default:
					break;
			}
		},

		uploadStarted:function(i, file, len){
			createImage(file);
		},

		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}

	});

	var template = '<div class="loader">'+
						'<div class="progress"></div>'+
				   '</div>';

	function createImage(file){

		var loader = $(template);

		var reader = new FileReader();

		// Reading the file as a DataURL. When finished,
		// this will trigger the onload function above:
		reader.readAsDataURL(file);

		message.hide();
		loader.appendTo('#loader');

		// Associating a loader container
		// with the file, using jQuery's $.data():
		$.data(file,loader);
	}

	function showMessage(msg){
		message.html(msg);
	}

});
