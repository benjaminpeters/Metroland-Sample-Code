//
//  HomeModel.swift
//  SwiftDatabaseTest
//
//  Created by Ben Peters on 2016-05-11.
//  Copyright © 2016 Orange Chips. All rights reserved.
//

import Foundation

protocol HomeModelProtocal: class {
    func itemsDownloaded(items: NSArray)
}


class HomeModel: NSObject, NSURLSessionDataDelegate {
    
    //properties
    
    weak var delegate: HomeModelProtocal!
    
    var data : NSMutableData = NSMutableData()
    
    let urlPath: String = "http://benpeters.ca/service.php" //path where service.php lives
    
    func downloadItems() {
        
        let url: NSURL = NSURL(string: urlPath)!
        var session: NSURLSession!
        let configuration = NSURLSessionConfiguration.defaultSessionConfiguration()
        
        
        session = NSURLSession(configuration: configuration, delegate: self, delegateQueue: nil)
        
        let task = session.dataTaskWithURL(url)
        
        task.resume()
        
    }
    
    func URLSession(session: NSURLSession, dataTask: NSURLSessionDataTask, didReceiveData data: NSData) {
        self.data.appendData(data);
        
    }
    
    func URLSession(session: NSURLSession, task: NSURLSessionTask, didCompleteWithError error: NSError?) {
        if error != nil {
            print("Failed to download data")
        }else {
            print("Data downloaded")
            self.parseJSON()
        }
        
    }
    
    func parseJSON() {
        
        var jsonResult: NSMutableArray = NSMutableArray()
        
        do{
            jsonResult = try NSJSONSerialization.JSONObjectWithData(self.data, options:NSJSONReadingOptions.AllowFragments) as! NSMutableArray
            
        } catch let error as NSError {
            print(error)
            
        }
        
        var jsonElement: NSDictionary = NSDictionary()
        let clothes: NSMutableArray = NSMutableArray()
        
        print(jsonResult.count)
        for(var i = 0; i < jsonResult.count; i++)
        {
            
            jsonElement = jsonResult[i] as! NSDictionary
            print(jsonElement["name"])
            let clothing = ClothingModel()
            
            //the following insures none of the JsonElement values are nil through optional binding
            let clothingID = jsonElement["clothingID"] as? String
                let name = jsonElement["name"] as? String
                let type = jsonElement["type"] as? String
                let price = jsonElement["price"] as? Float
                let image = jsonElement["image"] as? String
            
                
                clothing.clothingID = clothingID
                clothing.name = name
                clothing.type = type
                clothing.price = price
                clothing.image = image
                
            
            
            print(clothing)
            clothes.addObject(clothing)
            
        }
        
        dispatch_async(dispatch_get_main_queue(), { () -> Void in
            
            self.delegate.itemsDownloaded(clothes)
            
        })
    }
}