package com.udacoding.fcmproject.model

import android.os.Parcelable
import kotlinx.android.parcel.Parcelize

@Parcelize
data class ResponseMessage (
    val nama: String? = null,
    val email: String? = null
) : Parcelable