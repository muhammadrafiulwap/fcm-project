package com.udacoding.fcmproject

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import androidx.appcompat.app.AlertDialog
import com.google.firebase.iid.FirebaseInstanceId
import com.udacoding.fcmproject.model.ResponseMessage

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        onTokenRefresh()

        val data = intent.getParcelableExtra<ResponseMessage>("data")

        if (data != null) {
            AlertDialog.Builder(this).apply {
                title = "Message"
                setMessage("Name : ${data.nama}\n" +
                        "Email : ${data.email}")
                setPositiveButton("Okay") { dialogInterface, i ->
                    dialogInterface.dismiss()
                }.show()
            }
        }

    }

    private fun onTokenRefresh(){
        val refreshedToken = FirebaseInstanceId.getInstance().token

        Log.d("TAG", "onTokenRefresh: $refreshedToken")
    }

}




