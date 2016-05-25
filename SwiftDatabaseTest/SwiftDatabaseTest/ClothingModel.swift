//
//  ClothingModel.swift
//  SwiftDatabaseTest
//
//  Created by Ben Peters on 2016-05-11.
//  Copyright © 2016 Orange Chips. All rights reserved.
//

import Foundation

class ClothingModel: NSObject {
    
    //properties
    
    var clothingID: String?
    var name: String?
    var type: String?
    var price: Float?
    var image: String?
    
    
    //empty constructor
    
    override init()
    {
        
    }
    
    //construct with @name, @address, @latitude, and @longitude parameters
    
    init(clothingID:String, name: String, type: String, price: Float, image: String) {
        
        self.clothingID = clothingID
        self.name = name
        self.type = type
        self.price = price
        self.image = image
        
    }
    
    
    //prints object's current state
    
    override var description: String {
        return "ClothingID : \(clothingID),Name: \(name), Type: \(type), Price: \(price), Image: \(image)"
        
    }
    
    
}
