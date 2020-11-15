package com.udacoding.fcmproject

import android.app.Notification
import android.app.NotificationManager
import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.media.RingtoneManager
import android.util.Log
import androidx.appcompat.app.AlertDialog
import androidx.core.app.NotificationCompat
import com.google.firebase.messaging.FirebaseMessagingService
import com.google.firebase.messaging.RemoteMessage
import com.udacoding.fcmproject.model.ResponseMessage

class HandleMessageService : FirebaseMessagingService() {

    override fun onMessageReceived(remoteMessage: RemoteMessage) {
        super.onMessageReceived(remoteMessage)

        if (remoteMessage.data.isNotEmpty()) {
            Log.d("TAG", "onMessageReceived: ${remoteMessage.data}")

            val responseMessage = ResponseMessage(
                remoteMessage.data.get("nama"),
                remoteMessage.data.get("email")
            )

            playSound()

            val intent = Intent(this, MainActivity::class.java)
            intent.putExtra("data", responseMessage)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
            startActivity(intent)

            ringingNotification(
                remoteMessage.data.get("title").toString(),
                remoteMessage.data.get("body").toString(), intent
            )
        }
    }

    private fun ringingNotification(title: String, message: String, intent: Intent) {
        val pendingIntent = PendingIntent.getActivity(this, 0, intent, PendingIntent.FLAG_ONE_SHOT)

        val notif = NotificationCompat.Builder(this)
            .setContentTitle(title)
            .setContentText(message)
            .setSmallIcon(R.drawable.gallery)
            .setPriority(NotificationCompat.PRIORITY_HIGH)
            .setAutoCancel(true)
            .setContentIntent(pendingIntent)

        val buildNotif = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
        buildNotif.notify(0, notif.build())
    }

    private fun playSound() {
        val sound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION)
        val ringtoneSound = RingtoneManager.getRingtone(applicationContext, sound)
        ringtoneSound.play()
    }

    override fun onNewToken(newToken: String) {
        super.onNewToken(newToken)

        Log.d("TAG", "onNewToken: $newToken")

    }
}