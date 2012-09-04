var labelparents = [];
var mooaccordioncontainers = [];
var mooAccordion = [];
window.addEvent('domready',function(){
    var labels = $$('.mooaccordion');
    var lastcontainer = (labels.length > 0)?labels[0].getParent():null;
    var containerindex = 0;
    labels.each(function(label){
        if(lastcontainer != label.getParent()) {
            containerindex++;
            lastcontainer = label.getParent();
            labelparents[containerindex]=[];
        }
        if(labelparents.length == 0 || !labelparents[containerindex].contains(label)) {
            labelparents[containerindex]=label.getParent().getChildren('[class~="mooaccordion"]');
            mooaccordioncontainers[containerindex]=new Element('div',{'id':'mooaccordion'+containerindex,'class':'mooaccordioncontainer mooaccordioncontainer'+containerindex});
        }
    });
    labelparents.each(function(labelparent,index){
            if(labelparent[0].getChildren().length == 0 || !labelparent[0].getChildren()[0].hasClass('mooaccordionicon')) {
                mooaccordioncontainers[index].inject(labelparent[0],'before');
                labelparent.each(function(label){
                    var target = label.getNext();
                    label.addClass('mooaccordion'+index);
                    mooaccordioncontainers[index].adopt(label);
                    mooaccordioncontainers[index].adopt(target);
                    var icon = new Element('div',{'class':'mooaccordionicon'}).inject(label,'top');
                });
            }
    });
    mooaccordioncontainers.each(function(mooaccordioncontainer,index){
        if(mooaccordioncontainer.getParent()) {
            var containerid = mooaccordioncontainer.getParent().get('id')
        } else {
            var containerid = null;
        }
        var containeroptions = {};
        if(
            containerid!=null && 
            typeof(contentmooaccordion) !== 'undefined' && 
            typeof(contentmooaccordion) != 'undefined' && 
            typeof(contentmooaccordion[containerid])!='undefined'
        ) {
            // allow all hidden
            if(typeof(contentmooaccordion[containerid]['allowallclosed'])!='undefined') {
                containeroptions['alwaysHide']=true;
            }
            if(typeof(contentmooaccordion[containerid]['display']) != 'undefined') {
                // display specific item
                containeroptions['display']=contentmooaccordion[containerid]['display'];
            } else {
                // hide all
                if(typeof(contentmooaccordion[containerid]['allowallclosed'])!='undefined') containeroptions['display']=-1;
            }
            if(typeof(contentmooaccordion[containerid]['template'])!='undefined') {
                // you can't have both - if a template is selected, open and closed classes are overridden
                contentmooaccordion[containerid]['openedclass']='template'+contentmooaccordion[containerid]['template']+'opened';
                contentmooaccordion[containerid]['closedclass']='template'+contentmooaccordion[containerid]['template']+'closed';
            }
            if(
                    typeof(contentmooaccordion[containerid]['openedclass'])!='undefined' ||
                    typeof(contentmooaccordion[containerid]['closedclass'])!='undefined'
            ) {
                containeroptions['onActive']=function(toggler) {
                    if(typeof(contentmooaccordion[containerid]['openedclass'])!='undefined') {
                        toggler.addClass(contentmooaccordion[containerid]['openedclass']);
                    }else{
                       toggler.addClass('active'); 
                    }
                    if(typeof(contentmooaccordion[containerid]['closedclass'])!='undefined') {
                        toggler.removeClass(contentmooaccordion[containerid]['closedclass']);
                    }else{
                        toggler.removeClass('active');
                    }
                };
                containeroptions['onBackground']=function(toggler) {
                    if(typeof(contentmooaccordion[containerid]['openedclass'])!='undefined') {
                        toggler.removeClass(contentmooaccordion[containerid]['openedclass']);
                    }else{
                        toggler.removeClass('active');
                    }
                    if(typeof(contentmooaccordion[containerid]['closedclass'])!='undefined') {
                        toggler.addClass(contentmooaccordion[containerid]['closedclass']);
                    }else{
                       toggler.addClass('active'); 
                    }
                };
            }
        }
        if(Object.keys(containeroptions).length > 0) {
            mooAccordion[index] = new Fx.Accordion('#mooaccordion'+index+' .mooaccordion'+index,'*[class~=mooaccordion'+index+'] + *',containeroptions);
        } else {
            $$('.mooaccordion'+index).each(function(el){ el.addClass('mooaccordiondefaulttoggle');});
            mooAccordion[index] = new Fx.Accordion('#mooaccordion'+index+' .mooaccordion'+index,'*[class~=mooaccordion'+index+'] + *');
        }
    });
});