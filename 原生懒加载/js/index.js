function lazy(options){
	var tmp=options.id?document.getElementById(options.id):document;
	if(tmp===null) return;
	var imgs=tmp.getElementsByTagName("img"),
		imgobj=[],
		imgslen=imgs.length;
		for(var i=0;i<imgslen;i++){
			_img=imgs[i]
			if(_img.getAttribute("data-src") !== null){
				if(isLoad(_img)){
					setimg(_img)
				}else{
					imgobj.push(_img)
				}
			}
		}
	var len=imgobj.length;
	function hanble(){
		for(var i=0;i<len;i++){
			_imgobj=imgobj[i];
			if(isLoad(_imgobj)){
				_setimg(_imgobj)
				imgobj.splice(i,1)
				len--;
				if(len===0){lazyLoad()}
			}
		}
	}
	// ~~ 类型转换 转换为数字类型
	function isLoad(ele){
		// http://www.cnblogs.com/Sweety1024/p/6672262.html
		var scrollTop=document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
		if(typeof ele === "undefined") return false;
		var edit=~~ele.getAttribute("data-range") || options.lazyRange
		var clientHeight=scrollTop+document.documentElement.clientHeight+edit;
		var offsetTop=0;
		while(ele.tagName.toUpperCase() !== "BODY"){
			offsetTop+=ele.offsetTop;
			ele=ele.offsetParent; // http://www.jb51.net/article/45555.htm offsetParent 如果他的父盒子不进行定位 他会默认返回他的根元素
		}
		return (clientHeight>offsetTop)
	}
	function _setimg(ele){
		if(options.lazyTime){
			setTimeout(function(){
				setimg(ele)
			},options.lazyTime+ ~~ele.getAttribute("data-time"))
		}else{
			setimg(ele)
		}
	}
	function setimg(ele){
		ele.src=ele.getAttribute("data-src")
	}
	function lazyLoad(){
		// 删除事件监听
		window.removeEventListener?window.removeEventListener("scroll",hanble,false):window.detachEvent("onscroll",hanble)
	}
	// 添加事件监听
	window.addEventListener?window.addEventListener("scroll",hanble,false):window.attchEvent("onscroll",hanble)
}