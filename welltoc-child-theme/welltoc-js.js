jQuery(document).ready(function(){
  jQuery("#welltoc-skill-projects > .sc_skills_icon").removeClass("sc_skills_icon icon-vcard").addClass("welltoc-skills-projects-icon");
  jQuery("#welltoc-skill-clients > .sc_skills_icon").removeClass("sc_skills_icon icon-vcard").addClass("fa fa-4x fa-smile-o welltoc-skills-clients-icon");
// Menu language fix
var langContainer = jQuery(".wpglobus-current-language");
var targetLang = jQuery(".wpglobus-current-language > ul > li > a");
var dropDown = jQuery(".wpglobus-current-language > ul");
langContainer.children("a").remove();
langContainer.append(targetLang[0])
dropDown.remove();

 });
