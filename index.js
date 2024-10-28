const port = 4000;
const express = require("express");
const app = express();
const mongoose  = require('mongoose');
const jwt  = require('jsonwebtoken');
const multer  = require('multer') ;
const path  = require('path');
const cors  = require('cors');

app.use(express.json());
app.use(cors());

mongoose.connect("mongodb+srv://tharladanischal16:rahul123@cluster0.1gvst4t.mongodb.net/e-summerizer")

app.get("/",(req,res)=>{
    res.send("express is running")
})
const Users = mongoose.model('Users',{
    name:{
        type:String,
    },
    email:{
        type:String , 
        unique:true , 
    },
    password:{
        type:String,
    },
    date:{
        type:Date,
        default: Date.now(),
    }
})
app.post('/signup',async (req,res)=>{
    let check = await Users.findOne({email:req.body.email});
    if(check){
        return res.status(400).json({success:false,errors:'Email already exists'})
    }
    const user = new Users({
        name:req.body.username,
        email:req.body.email,
        password:req.body.password,

    })
    await user.save();

    const data = {
        user:{
            id:user.id
        }
    }
    const token = jwt.sign(data,'secret_esum');
    res.json({success:true,token})
})

app.post('/login',async (req,res)=>{
    let user =await Users.findOne({email:req.body.email});
    if (user) {
        const passcompare =req.body.password === user.password;
        if(passcompare) {
            const data = {
                user:{
                    id:user.id
                }
            }
            const token=jwt.sign(data,'secret_esum');
            res.json({success:true,token});
        }
        else{
            res.json({success:false,errors:"wrog password"});
        }
    }  
    else{
        res.json({success:false,errors:"User not found"});
    }

})

app.listen(port,(error)=>{
    if(!error) {
        console.log("Server is running on port"+port)
    }
    else{
        console.log("Error : "+ error);
    }
})

