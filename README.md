# Commentary Translation - Indigitous Hackathon 2016

## Overview

Commentary Translation would be a platform that enables people all around from the world to be able to read different commentaries in their own languages.

This would have two parts **web based platform** and **mobile application**. 

Web based platform would be the main place where in *commentaries* are documented both **written**, **audio** and **videos**. People around the world would be able to contribute by translating it to a language that is not yet there then it would be validated by administrators.

Mobile platform is created so that those commentaries would be *accessible* for people who wants to know more and missionaries around the world.

NOTE: This would required people collaboration so that it would be feasible.

### Skills Needed needed for this project:
- Web Developer (HTML5, CSS, Javascript, PHP, etc.)
- Mobile Developer (Android, iOS and Windows)
- Web Services API

## Key Features

### 1. Documented Commentaries (Written, Audio and Video)

This would allow the web based platform to be like a repository for commentaries that are meant to be shared. This would be taken from user inputs, API and etc.

### 2. Translated Commentaries

The commentaries stored then would be translated so that people around the world can use this as a reference to understand more about a specific things they have in mind.

### 3. Mobile Application - Offline Mode

This features would be important so that missionaries or people from hidden or urban areas that have limited/no internet connection would be able to use the mobile application. It would allow the missionary to use digital references.

## Database Structure (Draft - Only Main Feature)

![](ERD.png?raw=true)

## Restful API (Draft - Incomplete)

#### 1. Get Commentary List

List all the available commentary list.

** GET ** : /api/commentary/list

** Parameters **

| Name | Located In | Description | Required | Schema |
| --- | --- | --- | --- | | --- |
| type | query | Type of the commentary. | No | String |
| author | query | Author of the commentary. | No | String |
| chapter | query | Chapters related to the commentary. | No | Array String |
| verse | query | Verses related to the commentary . | No | Array String |

** Responses ( json ) - Example **

	{
	  "status" : 1,
	  "data" : {
	      "id" : "1", 
	      "title" : "Training"
	      "languages" : [
	          {
	            "value" : "English"
	          },
	          {
	            "value" : "Chinese"
	          },
	          {
	            "value" : "Arabic"
	          }
	        ]
	    },
	  "message" : "success"
	}

#### 2. Get Commentary Content

Display the content of the commentary.

** GET ** : /api/commentary/{id}/content

** Parameters **

| Name | Located In | Description | Required | Schema |
| --- | --- | --- | --- | | --- |
| id | path | Commentary id. | Yes | Integer |
| language | query | Language of the commentary. | Yes | String |
| section | query | Specific section of the commentary . | No | Array String |

** Responses ( json ) - Example **

	{
	  "status" : 1,
	  "data" : [
	      {
	        "id" : "1",
	        "title" : "First Comment",
	        "content" : "This is how he ...",
	        "srt_path" : ""
	      }
	    ],
	  "message" : "success"
	}
	

#### 3. Get Commentary Languages

Display all the available commentary languages.

** GET ** : /api/commentary/{id}/languages

** Parameters **

| Name | Located In | Description | Required | Schema |
| --- | --- | --- | --- | | --- |
| id | path | Commentary id. | Yes | Integer |

** Responses ( json ) - Example **

	{
	  "status" : 1,
	  "data" : [
	      {
	        "id" : "1",
	        "value" : "English"
	      },
	      {
	        "id" : "2",
	        "value" : "Arabic"
	      }.
	      {
	        "id" : "3",
	        "value" : "Chinese"
	      },
	      {
	        "id" : "4",
	        "value" : "Tamil"
	      }	      
	    ],
	  "message" : "success"
	}
	
	
# ... (More Enhancement) ...