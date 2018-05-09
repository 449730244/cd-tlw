
    _doc=document;
    var Doc={};
    Doc.Create=function(tagName){
        return _doc.createElement(tagName);
    }
    Doc.Append=function(dom){
        _doc.body.appendChild(dom);
    }
    Doc.Get=function(_id){
        return _doc.getElementById(_id);
    }

//无限级 select 分类
    function Select(arr,chg){
        //获得子类 集合（select）
        var getSel=function(pid){
            var _select=Doc.Create("select");
            _select.setAttribute('class','col-xs-10 col-sm-5');
            _select.options.add(new Option('选择分类',-1));
            for(var i=0;i<arr.length;i++){
                if(arr[i].pid==pid){
                    _select.options.add(new Option(arr[i].name,arr[i].id));
                }
            }
            var delChildfun=function(obj){

                if(obj.child){
                    var _child=obj.child;

                    if(_child.parentNode){
                        _child.parentNode.removeChild(_child);
                    }
                    delChildfun(_child);
                }
            }

            _select.onchange=function(){
                delChildfun(this);
                this.child = getSel(this.options[this.selectedIndex].value);
                chg(this.child);
            }

            return _select;

        }

        //===================获得节点
        var r_arr=[];
        var getPidById=function(id){
            for(var i=0;i<arr.length;i++)
                if(arr[i].id==id) return arr[i].pid;

            return -1;
        }

        var getSelBySid=function(sid){
            var pid = getPidById(sid);

            var sel = getSel(pid);
            for(var i=0;i<sel.options.length;i++) {
                if(sel.options[i].value==sid) {
                    sel.selectedIndex=i; break;
                }
            }

            if(pid>0) getSelBySid(pid);
            r_arr.push(sel);
        }

        this.getDom=function(selectid){
            getSelBySid(selectid||arr[0].id);
            for(var i=0;i<r_arr.length;i++)
                if(i+1<r_arr.length)
                    r_arr[i].child=r_arr[i+1];

            return r_arr;
        }
    }




