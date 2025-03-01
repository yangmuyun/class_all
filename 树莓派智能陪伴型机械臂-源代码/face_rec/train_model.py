import cv2
import numpy as np

# 加载训练好的模型
recognizer = cv2.face.LBPHFaceRecognizer_create()
recognizer.read("face_recognizer_model.yml")  # 加载模型

# 加载 Haar 级联分类器用于人脸检测
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")

# 标签字典（根据训练时的 label_dict 填写）
label_dict = {
    0: "person1",
    1: "person2",
    2: "person3",
}

# 打开摄像头
cap = cv2.VideoCapture(0)  # 0 表示默认摄像头

while True:
    # 读取一帧
    ret, frame = cap.read()
    if not ret:
        print("无法读取摄像头画面")
        break

    # 转换为灰度图像
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

    # 检测人脸
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))

    # 对每个检测到的人脸进行识别
    for (x, y, w, h) in faces:
        face_roi = gray[y:y+h, x:x+w]  # 提取人脸区域

        # 使用模型进行预测
        label, confidence = recognizer.predict(face_roi)

        # 显示识别结果
        if confidence < 100:  # 置信度阈值
            name = label_dict.get(label, "Unknown")
            confidence_text = f"{name} ({confidence:.2f})"
        else:
            confidence_text = "Unknown"

        # 在图像上绘制矩形和文本
        cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
        cv2.putText(frame, confidence_text, (x, y-10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)

    # 显示画面
    cv2.imshow("Face Recognition", frame)

    # 按下 'q' 键退出
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# 释放摄像头并关闭窗口
cap.release()
cv2.destroyAllWindows()