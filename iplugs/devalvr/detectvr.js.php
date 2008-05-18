<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Grain Theme for WordPress is distributed in the hope that it will 
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
	of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/

	
	@require_once('../../../../../wp-blog-header.php');

?>
//
//  DevalVR + QuickTime + PTviewer + Flash + Spi-v  Javascript workaround
//	Author: Armando Saenz (aka fiero) http://www.devalvr.com
//  Version: 1.1.6
//
//	p2q_... functions based on Thomas Rauscher's code: 
//	Thomas Rauscher <rauscher@pano2qtvr.com> http://www.pano2qtvr.com
//		
//		
//	To embed a panorama just include these lines in your HTML file (change filenames):
//
//	<SCRIPT type="text/javascript" src="detectvr.js"></SCRIPT>
//	<SCRIPT type="text/javascript">
//		writecode("fileForQT.mov","fileForDevalVR.mov","fileForJava.jpg","fileForFlash.swf","fileForSPIV.jpg","100%","94%");
//	</SCRIPT>
//
//
//	NOTE: To use Spi-v viewer, place "SPi-V.dcr" file in same folder that HTML file, you can find it here: http://www.fieldofview.com
//	
// 	IMPORTANT: Use this file at your own risk
//

var minQTVersion = "5.0.0";			// minimal required version checked for QuickTime
var minDevalVRVersion = "0,5,0,0";  // minimal required version checked for DevalVR
var minFlashVersion = "8.0.0";		// minimal required version checked for Flash
var minShockwaveVersion = "10.1";	// minimal required version checked for Shockwave

var pluginPriority_QuickTime=2;		// Set the order of Priority to use in each plugin 
var pluginPriority_DevalVR=1;		// when Autodetect option is selected. Change 
var pluginPriority_Flash=4;			// the number or priority for each plugin
var pluginPriority_Java=5;
var pluginPriority_Spiv=6;
var pluginPriority_PangeaVR=3;

var enableSizeLimits=0;				// 0: disable  1: enable  (size limits for QT, DevalVR and Spi-v)
var enableSizeLimitsJava=0;			// 0: disable  1: enable  (size limits for Java)
var enableSizeLimitsFlash=0;		// 0: disable  1: enable  (size limits for Flash)
var maxViewerWidth="1280";
var maxViewerHeight="1024";
var maxViewerWidthJava="900";
var maxViewerHeightJava="700";
var maxViewerWidthFlash="900";
var maxViewerHeightFlash="700";

var enableSizeRatio=0;				// 0: disable size ratio    1: enable size ratio
var sizeRatio=6/3;					// maximum aspect ratio, horizontal/vertical proportions

var enableLineUnderPanorama=0;		//Enable an adviser line under panorama
var writeLineUnderQuickTime="";
var writeLineUnderDevalVR="";
var writeLineUnderJava="<FONT face='Verdana' size='1' color='#cdcdcd'> Install the DevalVR plugin for an optimal viewing experience <A href='http://www.devalvr.com/install/'>click here to install</A>, and <A href='javascript:reloadPage()'>click here after installing</A></FONT>";
var writeLineUnderFlash="<FONT face='Verdana' size='1' color='#cdcdcd'> Install the DevalVR plugin for an optimal viewing experience <A href='http://www.devalvr.com/install/'>click here to install</A>, and <A href='javascript:reloadPage()'>click here after installing</A></FONT>";
var writeLineUnderSpiV="";
var writeLineUnderPangeaVR="";

var installfont="<FONT face='Verdana' size='2' color='#FFFFFF'>";

var usePurePlayer=0;						//0: PTViewer is used as Java player  1: PurePlayer is used (write correct names below)
var archivePurePlayer='PurePlayer.jar';		//Copy this file in the same folder
var codePurePlayer='PurePlayer.class';

var detectvr_replacepage=0;		// 1 to use window.location.replace() function to open a new page for each viewer (if 1 then writecode parameters must be the name of HTML pages)

/////////////////////////////////////////////////////////////////////////////////////////////////

//Internal variables
var isOpera=(navigator.userAgent.indexOf('Opera')!=-1);
var isIE=((navigator.appVersion.indexOf("MSIE") != -1) && !isOpera);
var isDOM=document.getElementById?1:0;
var isNS4=navigator.appName=='Netscape'&&!isDOM?1:0;
var isIE4=isIE&&!isDOM?1:0;
var isWindows=(navigator.platform.indexOf('Win')!=-1);
var isMac=(navigator.platform.indexOf('Mac')!=-1 || navigator.platform.indexOf('PowerPC')!=-1);
var favoriteViewer=getCookie("panorama_viewer");
if(!favoriteViewer) favoriteViewer="DETECT";  //DEVALVR , QT, FLASH, JAVA, SPIV, PANGEAVR, or DETECT
var detectableWithVB = false;
var adviselineunderpano="";
var orgsizepluginx=new Array();
var orgsizepluginy=new Array();
var numberofplugins=0;
var sizepluginx;
var sizepluginy;
var writePluginVR=0;
var writeInstallPluginVR=0;
var reloadpagewhenchangeviewer=0;
var auxparameters=new Array();
auxparameters['devalvr']=new Array();
auxparameters['qt']=new Array();
auxparameters['java']=new Array();
auxparameters['flash']=new Array();
auxparameters['spiv']=new Array();


//Use 'viewerparameters' function to define different parameters for each viewer
//Use "devalvr", "qt", "java", "flash", "spiv" or "pangeavr" in first parameter
//for example: viewerparameters("devalvr","resize","0","autoplay","3");

function viewerparameters(viewer)
{
	auxparameters[viewer]=new Array();
	for(var i=1;i<arguments.length;i++) 
	{
		auxparameters[viewer][i-1]=arguments[i];
	}
}

function writecode(qtfile, devalvrfile, javafile, flashfile, spivfile, sizex, sizey)
{
	writecode2(qtfile, qtfile, devalvrfile, javafile, flashfile, spivfile, sizex, sizey);
}

function writecode2(qtfile, pangeafile, devalvrfile, javafile, flashfile, spivfile, sizex, sizey)
{
	var priority=new Array();
	var existfile=new Array();
	writePluginVR=0;
	writeInstallPluginVR=0;
	
	existfile[1]=(devalvrfile!=null && devalvrfile!="");
	existfile[2]=(qtfile!=null && qtfile!="");
	existfile[3]=(flashfile!=null && flashfile!="");
	existfile[4]=(javafile!=null && javafile!="");
	existfile[5]=(spivfile!=null && spivfile!="");
	existfile[6]=(pangeafile!=null && pangeafile!="");
	
	if((favoriteViewer=="DEVALVR" && (!existfile[1] || !isWindows))
	|| (favoriteViewer=="QT" && !existfile[2])
	|| (favoriteViewer=="JAVA" && !existfile[4]) 
	|| (favoriteViewer=="FLASH" && !existfile[3]) 
	|| (favoriteViewer=="SPIV" && !existfile[5])
	|| (favoriteViewer=="PANGEAVR" && !existfile[6]))
		favoriteViewer="DETECT";
	
	priority[1]=pluginPriority_DevalVR;
	priority[2]=pluginPriority_QuickTime;
	priority[3]=pluginPriority_Flash;
	priority[4]=pluginPriority_Java;
	priority[5]=pluginPriority_Spiv;
	priority[6]=pluginPriority_PangeaVR;
	
	if(favoriteViewer=="DETECT")
	{
		for(order=1;order<=6 && writePluginVR==0;order++)
		{
			for(n=1;n<=6;n++)
			{
				if(priority[n]==order && existfile[n] && IsPluginInstalled(n))
				{
					writePluginVR=n;
					break;
				}
			}
		}
	}
	else if(favoriteViewer=="DEVALVR" && isWindows)
	{
		if(IsPluginInstalled(1)) writePluginVR=1;
		else writeInstallPluginVR=1;
	}
	else if(favoriteViewer=="QT" && (isWindows || isMac))
	{
		if(IsPluginInstalled(2)) writePluginVR=2;
		else writeInstallPluginVR=2;
	}
	else if(favoriteViewer=="FLASH" && (isWindows || isMac))
	{
		if(IsPluginInstalled(3)) writePluginVR=3;
		else writeInstallPluginVR=3;
	}
	else if(favoriteViewer=="JAVA") 
	{
		if(IsPluginInstalled(4)) writePluginVR=4;
		else writeInstallPluginVR=4;
	}
	else if(favoriteViewer=="SPIV" && (isWindows || isMac)) 
	{
		if(IsPluginInstalled(5)) writePluginVR=5;
		else writeInstallPluginVR=5;
	}
	else if(favoriteViewer=="PANGEAVR" && isMac) 
	{
		if(IsPluginInstalled(6)) writePluginVR=6;
		else writeInstallPluginVR=6;
	}
	
	if(writeInstallPluginVR==0 && writePluginVR==0)
	{
		if(isWindows) writeInstallPluginVR=1;	//Install DevalVR if there is not any plugin (fastest installation)
		else if(isMac) writeInstallPluginVR=2;	//Install QT always in Mac
		else writeInstallPluginVR=4;			//Install Java always in Linux
	} 
	
	adviselineunderpano="";
	if(enableLineUnderPanorama)
	{
		if(writePluginVR==1 && writeLineUnderDevalVR!="") adviselineunderpano=writeLineUnderDevalVR;
		else if(writePluginVR==2 && writeLineUnderQuickTime!="") adviselineunderpano=writeLineUnderQuickTime;
		else if(writePluginVR==3 && writeLineUnderFlash!="") adviselineunderpano=writeLineUnderFlash;
		else if(writePluginVR==4 && writeLineUnderJava!="") adviselineunderpano=writeLineUnderJava;
		else if(writePluginVR==5 && writeLineUnderSpiV!="") adviselineunderpano=writeLineUnderSpiV;
		else if(writePluginVR==6 && writeLineUnderPangeaVR!="") adviselineunderpano=writeLineUnderPangeaVR;
	}

	if(sizex=="") sizex="100%";
	if(sizey=="") sizey="94%";

	orgsizepluginx[numberofplugins]=sizex;
	orgsizepluginy[numberofplugins]=sizey;
	
	CalcLimits(sizex,sizey);
	sizex=sizepluginx;
	sizey=sizepluginy;

	idpano='panorama-applet';
	if(numberofplugins>0) idpano+=numberofplugins;
	numberofplugins++;
	
	if(writePluginVR==1)
	{
		if(detectvr_replacepage) window.location.replace(devalvrfile); 
		else p2q_EmbedDevalVR(devalvrfile,sizex,sizey,idpano,'filter','0');
	}
	else if(writePluginVR==2)
	{
		if(detectvr_replacepage) window.location.replace(qtfile); 
		else p2q_EmbedQuicktime(qtfile,sizex,sizey,idpano,'scale','tofit','autostart','true','kioskmode','true','controller', 'true');
	}
	else if(writePluginVR==3)
	{
		if(detectvr_replacepage) window.location.replace(flashfile); 
		else p2q_EmbedFlash(flashfile,sizex,sizey,idpano,'bgcolor', '#f0f0f0', 'play', 'true', 'cache','true', 'autoplay','true');
	}
	else if(writePluginVR==4)
	{
		if(detectvr_replacepage) window.location.replace(javafile); 
		else 
		{
			if(usePurePlayer==0) p2q_EmbedPtviewer(javafile,sizex,sizey,idpano,'fov','120','cursor','MOVE','showToolbar','true','imgLoadFeedback','false','pan','120');
			else p2q_EmbedPurePlayer(javafile,sizex,sizey,idpano);
		}
	}
	else if(writePluginVR==5)
	{
		if(detectvr_replacepage) window.location.replace(spivfile); 
		else p2q_EmbedSPiV(spivfile,sizex,sizey,idpano);
	}
	else if(writePluginVR==6)
	{
		if(detectvr_replacepage) window.location.replace(pangeafile); 
		else p2q_EmbedPangea(spivfile,sizex,sizey,idpano,'maxtilt','0','mintilt','0','maxfov','0','minfov','0');
	}
	else if(writeInstallPluginVR==1)
	{
		var str='<table border="0" cellpadding="0" cellspacing="0" style="WIDTH: ' + sizex + '; HEIGHT: ' + sizey + '" width="'+sizex+'"  height="'+sizey+'"><tr><td align="center" valign="middle">';
		str+='DevalVR plugin is required to see this content';
		str+='<BR><BR><A href="http://www.devalvr.com/install/"><?php 
			_e("click here to install (250 KB, it only takes a few seconds)"); 
		?></A>';
		str+='<BR><BR><A href="javascript:reloadPage()"><?php 
			_e("click here after installing to reload page"); 
		?></A>';
		str+='</td></tr></table>';
  
		document.writeln(str);
	}
	else
	{
		var pluginname,pluginurl;
		
		if(writeInstallPluginVR==2) 
		{
			pluginname="QuickTime Player"
			pluginurl="http://www.apple.com/quicktime/download/"
		}
		else if(writeInstallPluginVR==3) 
		{
			pluginname="Adobe Flash Player Version " + minFlashVersion + " or higher."
			pluginurl="http://www.adobe.com/go/getflash/"
		}
		else if(writeInstallPluginVR==4) 
		{
			pluginname="Java Software"
			pluginurl="http://java.com/en/download/"
		}
		else if(writeInstallPluginVR==5) 
		{
			pluginname="Shockwave Player"
			pluginurl="http://www.macromedia.com/shockwave/download"
		}
		else if(writeInstallPluginVR==6) 
		{
			pluginname="PangeaVR Player"
			pluginurl="http://www.pangeasoft.net/pano/plugin/downloads.html"
		}
		
		var str='<table border="0" cellpadding="0" cellspacing="0" style="WIDTH: ' + sizex + '; HEIGHT: ' + sizey + '" width="'+sizex+'"  height="'+sizey+'"><tr><td align="center" valign="middle">';
		str+=installfont+'This content requires '+ pluginname +'.';
		str+='<BR><BR><A href="'+ pluginurl +'">click here to install '+ pluginname +'</A>';
		str+='</FONT></td></tr></table>';
		document.writeln(str);
	}

	if(writeInstallPluginVR==0) window.onresize=OnResizeWindow;
}

function ShowViewerSelection(options)
{
	reloadpagewhenchangeviewer=(options.indexOf("reload")!=-1);
	options.toLowerCase();
	var vertical='';
	var combobox=(options.indexOf("combobox")!=-1);
	var str='';

	if(options.indexOf("horizontal")==-1)
		vertical='<BR>';
	
	viewer=getCookie("panorama_viewer");
	if(!viewer) viewer="DETECT";

	if(combobox)
	{
		str+='<SELECT id=comboboxViewer style="WIDTH: 160px" width=160 name=comboboxViewer onchange="favoriteViewerChangedCombo()">';
	}
	if(options.indexOf("detect")!=-1)
	{
		if(combobox) str+='<OPTION value="DETECT" '+(viewer=="DETECT"?'selected':'')+'> Automatic detection</OPTION>';
		else str+='<INPUT id=radioViewer value=1 type=radio name=radioViewer OnClick="javascript:changeFavoriteViewer(\'DETECT\');" '+(viewer=="DETECT"?'CHECKED':'')+'>Automatic detection'+vertical;
	}
	if(options.indexOf("devalvr")!=-1 && isWindows)
	{
		if(combobox) str+='<OPTION value="DEVALVR" '+(viewer=="DEVALVR"?'selected':'')+'> DevalVR</OPTION>';
		else str+='<INPUT id=radioViewer value=2 type=radio name=radioViewer OnClick="javascript:changeFavoriteViewer(\'DEVALVR\');" '+(viewer=="DEVALVR"?'CHECKED':'')+'>DevalVR '+vertical;
	}
	if(options.indexOf("qt")!=-1)
	{
		if(combobox) str+='<OPTION value="QT" '+(viewer=="QT"?'selected':'')+'> QuickTime</OPTION>';
		else str+='<INPUT id=radioViewer value=3 type=radio name=radioViewer OnClick="javascript:changeFavoriteViewer(\'QT\');" '+(viewer=="QT"?'CHECKED':'')+'>QuickTime '+vertical;
	}
	if(options.indexOf("flash")!=-1)
	{
		if(combobox) str+='<OPTION value="FLASH" '+(viewer=="FLASH"?'selected':'')+'> Flash</OPTION>';
		else str+='<INPUT id=radioViewer value=4 type=radio name=radioViewer OnClick="javascript:changeFavoriteViewer(\'FLASH\');" '+(viewer=="FLASH"?'CHECKED':'')+'>Flash '+vertical;
	}
	if(options.indexOf("java")!=-1)
	{
		if(combobox) str+='<OPTION value="JAVA" '+(viewer=="JAVA"?'selected':'')+'> Java</OPTION>';
		else str+='<INPUT id=radioViewer value=5 type=radio name=radioViewer OnClick="javascript:changeFavoriteViewer(\'JAVA\');" '+(viewer=="JAVA"?'CHECKED':'')+'>Java '+vertical;
	}
	if(options.indexOf("spiv")!=-1 || options.indexOf("spi-v")!=-1)
	{
		if(combobox) str+='<OPTION value="SPIV" '+(viewer=="SPIV"?'selected':'')+'> Spi-V</OPTION>';
		else str+='<INPUT id=radioViewer value=6 type=radio name=radioViewer OnClick="javascript:changeFavoriteViewer(\'SPIV\');" '+(viewer=="SPIV"?'CHECKED':'')+'>Spi-V '+vertical;
	}
	if(options.indexOf("pangeavr")!=-1 && isMac)
	{
		if(combobox) str+='<OPTION value="PANGEAVR" '+(viewer=="PANGEAVR"?'selected':'')+'> PangeaVR</OPTION>';
		else str+='<INPUT id=radioViewer value=7 type=radio name=radioViewer OnClick="javascript:changeFavoriteViewer(\'PANGEAVR\');" '+(viewer=="PANGEAVR"?'CHECKED':'')+'>PangeaVR '+vertical;
	}
	if(combobox)
	{
		str+='</SELECT><BR>';
	}
	else if(vertical=='') str+='<BR>';

	document.writeln('<FONT id=IDVIEWEROPTIONS>&nbsp;</FONT>');
	ref=getRef("IDVIEWEROPTIONS");
	if(ref)	ref.innerHTML=str;
}

function favoriteViewerChangedCombo()
{
	ref=getRef("comboboxViewer");
	if(ref) changeFavoriteViewer(ref.value);
}

function changeFavoriteViewer(viewer)
{
	if(favoriteViewer!=viewer)
	{
		setCookie("panorama_viewer",viewer);
		if(reloadpagewhenchangeviewer)
			window.location.reload();
	}
}

//Old version, it exist for compatibility with old pages
function writevrcode(movfile,javafile,flashfile,spivfile,width,height)
{
	var sizex=0,sizey=0;
	
	//Compatibility code, old versions of writevrcode only was 
	//qtfile and javafile parameters: writevrcode(movfile,javafile,width,height)
	
	var paramsize=2;
	for(var n=2;n<4;n++)
	{
		if(arguments[n]!=null)
		{
			var str=arguments[n].toString();
			str.toLowerCase();
			if(n==2)
			{
				if(str.indexOf(".swf")!=-1 || str=="") paramsize++;
				else flashfile="";
			}
			else if(n==3)
			{
				if(str.indexOf(".spv")!=-1 || str.indexOf(".xml")!=-1 || str.indexOf(".jpg")!=-1 || str=="")
				{
					if(paramsize==2) paramsize++;
					paramsize++;
				}
				else spivfile="";
			}
		}
	}
	if(arguments[paramsize]!=null)
		sizex=arguments[paramsize];
	if(arguments[paramsize+1]!=null)
		sizey=arguments[paramsize+1];
	
	var qtfile=movfile;	
	if(movfile.toLowerCase().indexOf(".mov")==-1)
		qtfile="";

	writecode(qtfile,movfile,javafile,flashfile,spivfile,sizex,sizey);
}

function IsPluginInstalled(numplugin)
{
	var installed=0;
	
	if(isWindows && isIE)
	{
		if(detectableWithVB)
		{
			if(numplugin==1)
			{
				installed=detectActiveXControl('DevalVRXCtrl.DevalVRXCtrl.1');
			}
			else if(numplugin==2)
			{
				installed=detectQuickTimeActiveXControl();
			}
			else if(numplugin==3)
			{
				var flashVersion=minFlashVersion.split(".");
				installed =DetectFlashVer(parseInt(flashVersion[0]),parseInt(flashVersion[1]),parseInt(flashVersion[2])); 
			}
			else if(numplugin==4)
			{
				installed=detectActiveXControl('JavaPlugin') && navigator.javaEnabled();
			}
			else if(numplugin==5)
			{
				var strswversion=minShockwaveVersion.split(".");
				var strshockwave="";
				do{
					strshockwave="SWCtl.SWCtl."+strswversion[0]+"."+strswversion[1]+".1";
					strswversion[1]++;
					if(strswversion[1]>9)
					{
						strswversion[1]=0;
						strswversion[0]++;
					}
					installed=detectActiveXControl(strshockwave);
				}while(installed==0 && strswversion[0]<20);
			}
		}
	}
	else
	{
		if(numplugin==1)
		{
			if(navigator.mimeTypes && navigator.mimeTypes["application/x-devalvrx"] && navigator.mimeTypes["application/x-devalvrx"].enabledPlugin)
			{
				var words = navigator.plugins["DevalVR 3D Plugin"].description.split(" ");
				var version = words[3].split(",");
				var min = minDevalVRVersion.split(",");
				installed=checkMinVersion(version,min,4);
			}	
		}
		else if(numplugin==2)
		{
			if(navigator.mimeTypes && navigator.mimeTypes["video/quicktime"] && navigator.mimeTypes["video/quicktime"].enabledPlugin)
			{
				for (var i = 0; i < navigator.plugins.length && installed==0; i++)
				{
					if(navigator.plugins[i].name.indexOf("QuickTime Plug-in")!=-1)
					{
						var words = navigator.plugins[i].name.split(" ");
						if (words.length<3) installed=1;
						else
						{
							var version = words[2].split(".");
							var min = minQTVersion.split(".");
							installed=checkMinVersion(version,min,3);
						}
						break;
					}
				}
			}
		}
		else if(numplugin==3)
		{
			var flashVersion=minFlashVersion.split(".");
			installed = DetectFlashVer(parseInt(flashVersion[0]),parseInt(flashVersion[1]),parseInt(flashVersion[2])); 
		}
		else if(numplugin==4)
		{
			if (isOpera || (navigator.mimeTypes && navigator.mimeTypes['application/x-java-applet'] && navigator.mimeTypes["application/x-java-applet"].enabledPlugin))
			{
				installed=navigator.javaEnabled();
			}
		}
		else if(numplugin==5)
		{
			if(navigator.mimeTypes && navigator.mimeTypes["application/x-director"] && navigator.mimeTypes["application/x-director"].enabledPlugin)
			{
				var description=navigator.plugins["Shockwave for Director"].description;
				var pos=description.indexOf("version");
				if (pos!=-1)
				{
					var words=description.substr(pos+8);
					var version = words.split(".");
					var min = minShockwaveVersion.split(".");
					installed=checkMinVersion(version,min,2);
				}
			}
		}
		else if(numplugin==6)
		{
			if(navigator.mimeTypes && navigator.mimeTypes["graphics/pangeavr2"] && navigator.mimeTypes["graphics/pangeavr2"].enabledPlugin)
			{
				installed=1;
			}
		}
	}
	return installed;
}

function OnResizeWindow()
{
	for(n=0;n<numberofplugins;n++)
	{
		CalcLimits(orgsizepluginx[n],orgsizepluginy[n]);

		if(n==0) ref=getRef("PANORAMAID");
		else ref=getRef("PANORAMAID"+n);
		if(ref!=null)
		{
			if(isIE || isOpera)
			{
				ref.style.width=sizepluginx;
				ref.style.height=sizepluginy;
			}
			else
			{	
				ref.width=sizepluginx;
				ref.height=sizepluginy;
			}
		}
	}
}

function checkMinVersion(version,min,num)
{
	var installed=0;
	for (var i = 0; i < num; i++)
	{
		if(parseInt(version[i])>parseInt(min[i])) 
		{
			installed=1;
			break;
		}
		else if(parseInt(version[i])<parseInt(min[i]))
		{
			break;
		}
		else if(parseInt(version[i])==parseInt(min[i]) && i==num-1)
			installed=1;
	}
	return installed;
}

function CalcLimits(sizex, sizey)
{
	var sizew,sizeh,limits;
	
	if(writePluginVR==4)
	{
		maxx=maxViewerWidthJava;
		maxy=maxViewerHeightJava;
		limits=enableSizeLimitsJava;
	}
	else if(writePluginVR==3)
	{
		maxx=maxViewerWidthFlash;
		maxy=maxViewerHeightFlash;
		limits=enableSizeLimitsFlash;
	}
	else
	{
		maxx=maxViewerWidth;
		maxy=maxViewerHeight;
		limits=enableSizeLimits;
	}

	if(sizex<0) sizex=getPageWidth()+parseInt(sizex);
	if(sizey<0) sizey=getPageHeight()+parseInt(sizey);
	
	if(limits || enableSizeRatio || adviselineunderpano!="")
	{
		sizex=sizex.toString();
		sizey=sizey.toString();
		pw=sizex.indexOf("px");
		if(pw!=-1) sizex=sizex.substring(0,pw);
		pw=sizey.indexOf("px");
		if(pw!=-1) sizey=sizey.substring(0,pw);
	
		sizew=getPageWidth();
		sizeh=getPageHeight();
	
		pw=sizex.indexOf("%");
		if(pw!=-1)
		{
			percent=sizex.substring(0,pw);
			sizex=(sizew*percent)/100;
		}
		pw=sizey.indexOf("%");
		if(pw!=-1)
		{
			percent=sizey.substring(0,pw);
			sizey=(sizeh*percent)/100;
		}
	
		sizex=parseInt(sizex);
		sizey=parseInt(sizey);

		if(limits)
		{
			if(sizex>maxx) sizex=maxx;
			if(sizey>maxy) sizey=maxy;
		}	
		if(enableSizeRatio)
		{
			x=sizey*sizeRatio;
			if(x<sizex)
			{
				sizex=x;
				if(limits && sizex>maxx) sizex=maxx;
			}
		}
		if(adviselineunderpano!="")
		{
			sizey-=16;
		}
	}

/*	if(limits || enableSizeRatio || adviselineunderpano!="")
	{
		sizex=((sizex*100.0)/sizew);
		sizey=((sizey*100.0)/sizeh);
		if(sizex<1) sizex=1; 
		if(sizex>100) sizex=100; 
		if(sizey<1) sizey=1; 
		if(sizey>100) sizey=100;
		sizex=parseInt(sizex)+"%";
		sizey=parseInt(sizey)+"%";
	}
*/
	sizepluginx=sizex;
	sizepluginy=sizey;

}

function reloadPage()
{
	if(!isIE) navigator.plugins.refresh(true);
	window.location.reload();
}

function getCookie(nombre)
{
	var dcookie=document.cookie;
	var cname=nombre+"=";
	var longitud=dcookie.length;
	var inicio=0;
	while(inicio<longitud)
	{
		var vbegin=inicio+cname.length;
		if(dcookie.substring(inicio,vbegin)==cname)
		{
			var vend=dcookie.indexOf(";",vbegin);
			if(vend==-1) vend=longitud;
			return unescape(dcookie.substring(vbegin,vend));
		}
		inicio=dcookie.indexOf(" ",inicio)+1;
		if(inicio==0) break;
	}
	return null;
}

function setCookie(name, value, expires)
{
	if(!expires)
	{ 
		expires=new Date();
		expires.setTime(expires.getTime()+(24*3600*1000*365));
	}
	document.cookie=name+"="+escape(value)+"; expires=" + expires.toGMTString()+ "; path=/";
}

function getPageHeight()
{
	var ret;
	if(isIE) ret=document.body.clientHeight-document.body.topMargin-document.body.bottomMargin;
	else ret=window.innerHeight-16;
	
	return ret;
}

function getPageWidth()
{
	var ret;
	if(isIE) ret=document.body.clientWidth-document.body.leftMargin-document.body.rightMargin;
	else ret=window.innerWidth;
	
	return ret;
}

function IEGetSwfVer()
{
	flashVer=0;
		
	for(i=25;i>0 && flashVer==0;i--)
	{
		flashVer=VBGetSwfVer(i);
	}
	return flashVer;
}

// JavaScript helper required to detect Flash Player PlugIn version information
function JSGetSwfVer()
{
	// NS/Opera version >= 3 check for Flash plugin in plugin array
	if (navigator.plugins != null && navigator.plugins.length > 0) {
		if (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]) {
			var swVer2 = navigator.plugins["Shockwave Flash 2.0"] ? " 2.0" : "";
      		var flashDescription = navigator.plugins["Shockwave Flash" + swVer2].description;
			descArray = flashDescription.split(" ");
			tempArrayMajor = descArray[2].split(".");
			versionMajor = tempArrayMajor[0];
			versionMinor = tempArrayMajor[1];
			if ( descArray[3] != "" ) {
				tempArrayMinor = descArray[3].split("r");
			} else {
				tempArrayMinor = descArray[4].split("r");
			}
      		versionRevision = tempArrayMinor[1] > 0 ? tempArrayMinor[1] : 0;
            flashVer = versionMajor + "." + versionMinor + "." + versionRevision;
      	} else {
			flashVer = -1;
		}
	}
	// MSN/WebTV 2.6 supports Flash 4
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.6") != -1) flashVer = 4;
	// WebTV 2.5 supports Flash 3
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.5") != -1) flashVer = 3;
	// older WebTV supports Flash 2
	else if (navigator.userAgent.toLowerCase().indexOf("webtv") != -1) flashVer = 2;
	// Can't detect in all other cases
	else {
		
		flashVer = -1;
	}
	return flashVer;
}
 
// If called with no parameters this function returns a floating point value 
// which should be the version of the Flash Player or 0.0 
// ex: Flash Player 7r14 returns 7.14
// If called with reqMajorVer, reqMinorVer, reqRevision returns true if that version or greater is available
function DetectFlashVer(reqMajorVer, reqMinorVer, reqRevision) 
{
 	reqVer = parseFloat(reqMajorVer + "." + reqRevision);
  	if (isIE && isWindows && !isOpera) {
		versionStr = IEGetSwfVer();
	} else {
		versionStr = JSGetSwfVer();		
	}
	if (versionStr == -1 ) 
	{ 
		return false;
	} 
	else if (versionStr != 0) 
	{
		if(isIE && isWindows && !isOpera) {
			tempArray         = versionStr.split(" ");
			tempString        = tempArray[1];
			versionArray      = tempString .split(",");				
		} else {
			versionArray      = versionStr.split(".");
		}
		versionMajor      = versionArray[0];
		versionMinor      = versionArray[1];
		versionRevision   = versionArray[2];
			
		versionString     = versionMajor + "." + versionRevision;   // 7.0r24 == 7.24
		versionNum        = parseFloat(versionString);
    	// is the major.revision >= requested major.revision AND the minor version >= requested minor
		if ( (versionMajor > reqMajorVer) && (versionNum >= reqVer) ) {
			return true;
		} else {
			return ((versionNum >= reqVer && versionMinor >= reqMinorVer) ? true : false );	
		}
	}
	return (reqVer ? false : 0.0);
}

function writeParameters(parameters, auxparameters, IEparameters)
{
	for(i=0;i<auxparameters.length;i+=2) 
	{
		if(IEparameters)
			document.writeln('  <param name="' + auxparameters[i] + '" value="' + auxparameters[i+1] + '">');
		else
			document.writeln('  ' + auxparameters[i] + '="' + auxparameters[i+1] + '"');
	}
	for(i=4;i<parameters.length;i+=2) 
	{
		var exists=0;
		for(j=0;j<auxparameters.length;j+=2) {
			if(auxparameters[j]==parameters[i]) exists=1;
		}
		if(exists==0)
		{
			if(IEparameters)
				document.writeln('  <param name="' + parameters[i] + '" value="' + parameters[i+1] + '">');
			else
				document.writeln('  ' + parameters[i] + '="' + parameters[i+1] + '"');
		}
	}
}

function p2q_EmbedQuicktime(sFile,sWidth,sHeight,sId) 
{
	document.writeln('<object id='+sId+' classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B"');
	document.writeln(' codebase="http://www.apple.com/qtactivex/qtplugin.cab"');
	document.writeln('  style="WIDTH: ' + sWidth + '; HEIGHT: ' + sHeight + '" width="' + sWidth + '" height="' + sHeight + '" >');
	document.writeln('  <param name="src" value="' + sFile + '">');
	
	writeParameters(arguments,auxparameters['qt'],1);
	
	document.writeln('<embed name='+sId+' width="' + sWidth + '" height="' + sHeight + '"');
	document.writeln('	pluginspage="http://www.apple.com/quicktime/download/"');
	document.writeln('	type="video/quicktime"');
	document.writeln('	src="' + sFile + '"');

	writeParameters(arguments,auxparameters['qt'],0);

	document.writeln('	/>');
	document.writeln('</object>');
	
	if(adviselineunderpano!="")
	{
		if(sWidth!="100%") document.writeln('<BR>');
		document.writeln(adviselineunderpano);
	}
}

function p2q_EmbedDevalVR(sFile,sWidth,sHeight,sId) 
{
	document.writeln('<object id='+sId+' classid="clsid:5D2CF9D0-113A-476B-986F-288B54571614"');
	document.writeln(' codebase="http://www.devalvr.com/instalacion/plugin/devalocx.cab');
	document.writeln('#version='+minDevalVRVersion+'"');
	document.writeln('  style="WIDTH: ' + sWidth + '; HEIGHT: ' + sHeight + '" width="' + sWidth + '" height="' + sHeight + '" >');
	document.writeln('  <param name="src" value="' + sFile + '">');
	writeParameters(arguments,auxparameters['devalvr'],1);
	document.writeln('<embed name='+sId+' width="' + sWidth + '" height="' + sHeight + '"');
	document.writeln('	pluginspage="http://www.devalvr.com/instalacion/plugin/install.html"');
	document.writeln('	type="application/x-devalvrx"');
	document.writeln('	src="' + sFile + '"');
	writeParameters(arguments,auxparameters['devalvr'],0);
	document.writeln('	/>');
	document.writeln('</object>');
	
	if(adviselineunderpano!="")
	{
		if(sWidth!="100%") document.writeln('<BR>');
		document.writeln(adviselineunderpano);
	}
}

function p2q_EmbedFlash(sFile,sWidth,sHeight,sId) 
{
	document.writeln('<object id='+sId+' classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"');
	document.writeln(' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab');
	document.writeln('  style="WIDTH: ' + sWidth + '; HEIGHT: ' + sHeight + '" width="' + sWidth + '" height="' + sHeight + '" >');
	document.writeln('  <param name="src" value="' + sFile + '">');
	writeParameters(arguments,auxparameters['flash'],1);
	document.writeln('<embed name='+sId+' width="' + sWidth + '" height="' + sHeight + '"');
	document.writeln('	pluginspage="http://www.macromedia.com/go/getflashplayer"');
	document.writeln('	type="application/x-shockwave-flash"');
	document.writeln('	src="' + sFile + '"');
	writeParameters(arguments,auxparameters['flash'],0);
	document.writeln('	/>');
	document.writeln('</object>');
	
	if(adviselineunderpano!="")
	{
		if(sWidth!="100%") document.writeln('<BR>');
		document.writeln(adviselineunderpano);
	}
}

function p2q_EmbedPtviewer(sFile,sWidth,sHeight,sId) 
{
	document.writeln('<applet name='+sId+' code="ptviewer.class" archive="<?php bloginfo('template_directory'); ?>/iplugs/ptviewer/ptviewer<?php echo GRAIN_PTVIEWER_FLAVOR; ?>.jar"'); 
	document.writeln('  width="' + sWidth + '" height="' + sHeight + '" >');
	document.writeln('	<param name="file" value="' + sFile + '">');
	writeParameters(arguments,auxparameters['java'],1);
	document.writeln('</applet>');
	
	if(adviselineunderpano!="")
	{
		if(sWidth!="100%") document.writeln('<BR>');
		document.writeln(adviselineunderpano);
	}
}

function p2q_EmbedPurePlayer(sFile,sWidth,sHeight,sId) 
{
	document.writeln('<applet name='+sId+' code="' + codePurePlayer + '" archive="' + archivePurePlayer + '"'); 
	document.writeln('  width="' + sWidth + '" height="' + sHeight + '" >');
	document.writeln('	<param name="panorama" value="' + sFile + '" >');
	document.writeln('	<param name="optimizememory" value="true" >');
	writeParameters(arguments,auxparameters['java'],1);
	document.writeln('</applet>');
	
	if(adviselineunderpano!="")
	{
		if(sWidth!="100%") document.writeln('<BR>');
		document.writeln(adviselineunderpano);
	}
}

function p2q_EmbedSPiV(sFile,sWidth,sHeight,sId) 
{
	document.writeln('<object id='+sId+' classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000"');
	document.writeln('codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0"');
	document.writeln('  style="WIDTH: ' + sWidth + '; HEIGHT: ' + sHeight + '" width="' + sWidth + '" height="' + sHeight + '" >');
	document.writeln('	<param name="src" value="SPi-V.dcr">');
	document.writeln('	<param name="swStretchStyle" value="stage">');
	document.writeln('	<param name="swRemote"       value="swContextMenu=' + "'" + 'FALSE' + "'" + '">');
	document.writeln('	<param name="progress"       value="true">'); 
	document.writeln('	<param name="logo"           value="false">'); 

	document.writeln('  <param name="swURL" value="' + sFile + '">');
	writeParameters(arguments,auxparameters['spiv'],1);
	document.writeln('<embed name='+sId+' width="' + sWidth + '" height="' + sHeight + '"');
	document.writeln('	pluginspage="http://www.macromedia.com/shockwave/download/"');
	document.writeln('	type="application/x-director" ');
	document.writeln('	swURL="' + sFile + '" ');
	document.writeln('	src="SPi-V.dcr" ');
	document.writeln('	swStretchStyle="stage" ');
	document.writeln('	swRemote="swContextMenu=' + "'" + 'FALSE' + "'" + '" ');
	document.writeln('	progress="true" ');
	document.writeln('	logo="false" ');
	writeParameters(arguments,auxparameters['spiv'],0);
	document.writeln('	/>');
	document.writeln('</object>');
	
	if(adviselineunderpano!="")
	{
		if(sWidth!="100%") document.writeln('<BR>');
		document.writeln(adviselineunderpano);
	}
}

function p2q_EmbedPangea(sFile,sWidth,sHeight,sId) 
{
	document.writeln('<embed name='+sId+' width="' + sWidth + '" height="' + sHeight + '"');
	document.writeln('	pluginspage="http://www.pangeasoft.net/pano/plugin/downloads.html"');
	document.writeln('	type="graphics/pangeavr2"');
	document.writeln('	src="' + sFile + '"');
	writeParameters(arguments,auxparameters['pangeavr'],0);
	document.writeln(' />');
	
	if(adviselineunderpano!="")
	{
		if(sWidth!="100%") document.writeln('<BR>');
		document.writeln(adviselineunderpano);
	}
}

// Here we write out the VBScript block for MSIE Windows
if (isWindows && isIE) 
{
    document.writeln('<script language="VBscript" type="text/vbscript">');

    document.writeln('\'do a one-time test for a version of VBScript that can handle this code \n');
    document.writeln('detectableWithVB = False \n');
    document.writeln('If ScriptEngineMajorVersion >= 2 then \n');
    document.writeln('  detectableWithVB = True \n');
    document.writeln('End If \n');

    document.writeln('\'this next function will detect most plugins \n');
    document.writeln('Function detectActiveXControl(activeXControlName) \n');
    document.writeln('  on error resume next \n');
    document.writeln('  detectActiveXControl = False \n');
    document.writeln('  If detectableWithVB Then \n');
    document.writeln('		set pControl = CreateObject(activeXControlName) \n');
    document.writeln('		If (IsObject(pControl)) then \n');
	document.writeln('			detectActiveXControl = True \n');
    document.writeln('		End If \n');
    document.writeln('  End If \n');
    document.writeln('End Function \n');

    document.writeln('\'and the following function handles QuickTime \n');
    document.writeln('Function detectQuickTimeActiveXControl() \n');
    document.writeln('  on error resume next \n');
    document.writeln('  detectQuickTimeActiveXControl = False \n');
    document.writeln('  If detectableWithVB Then \n');
    document.writeln('    detectQuickTimeActiveXControl = False \n');
    document.writeln('    hasQuickTimeChecker = false \n');
    document.writeln('    Set hasQuickTimeChecker = CreateObject("QuickTimeCheckObject.QuickTimeCheck.1") \n');
    document.writeln('    If IsObject(hasQuickTimeChecker) Then \n');
    document.writeln('      If hasQuickTimeChecker.IsQuickTimeAvailable(1) Then  \n');
    document.writeln('        detectQuickTimeActiveXControl = True \n');
    document.writeln('      End If \n');
    document.writeln('    End If \n');
    document.writeln('  End If \n');
    document.writeln('End Function \n');

    document.writeln('\'Visual basic helper required to detect Flash Player ActiveX control version information \n');
    document.writeln('Function VBGetSwfVer(i) \n');
    document.writeln('  on error resume next \n');
    document.writeln('  Dim swControl, swVersion \n');
    document.writeln('  swVersion = 0 \n');
    document.writeln('  If detectableWithVB Then \n');
    document.writeln('		set swControl = CreateObject("ShockwaveFlash.ShockwaveFlash." + CStr(i)) \n');
    document.writeln('		If (IsObject(swControl)) then \n');
    document.writeln('			swVersion = swControl.GetVariable("$version") \n');
    document.writeln('		End If \n');
    document.writeln('  End If \n');
    document.writeln('  VBGetSwfVer = swVersion \n');
    document.writeln('End Function \n');

    document.writeln('</scr' + 'ipt>');
}


//////////////// Special functions to create dynamic pages
function getQueryVariable(variable) 
{
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i< vars.length;i++) 
	{
		var pair = vars[i].split("=");
		if (pair[0] == variable) 
		{
			return pair[1];
		}
	} 
	return -1;
}	

function getRef(id) 
{
	return (isDOM ? document.getElementById(id) : (isIE4 ? document.all[id] : document.layers[id]));
}
function getStyle(id) 
{
	return (isNS4 ? getRef(id) : getRef(id).style);
}

var panoramadata=new Array();
var panoramanumdata=0;

function insertpanoramadata(name, panofile, title, description, date, jpgimage)
{
	var pano="pano"+panoramanumdata;
	panoramadata[pano]=name;
	panoramadata[name]=new Array();
	panoramadata[name]["movfile"]=panofile;
	panoramadata[name]["title"]=title;
	panoramadata[name]["description"]=description;
	panoramadata[name]["date"]=date;
	panoramadata[name]["jpgimage"]=jpgimage;
	
	panoramanumdata++;
}

///////////////////
