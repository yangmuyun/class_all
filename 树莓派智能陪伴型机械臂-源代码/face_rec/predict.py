import cv2
import numpy as np
from tensorflow.keras.models import load_model
import data_util

# 加载模型
model_path = 'trained_model.h5'  # 使用你刚刚保存的模型
model = load_model(model_path)

# 获取类别标签
train_dir = 'dataset'  # 根据实际情况调整路径
train_generator = data_util.load_and_preprocess_data(train_dir)
class_labels = {v: k for k, v in train_generator.class_indices.items()}  # 将索引转换为人名字典

cap = cv2.VideoCapture(0)

while True:
    ret, frame = cap.read()
    if not ret:
        print("无法获取摄像头画面")
        break

    # 预处理帧以适应模型输入
    img = cv2.resize(frame, (150, 150))

    # PGM 文件通常是灰度图像，而我们的模型期望的是RGB图像
    # 如果你的图像是灰度的，请将其转换为RGB格式
    if len(img.shape) == 2 or img.shape[2] == 1:
        img = cv2.cvtColor(img, cv2.COLOR_GRAY2RGB)

    img_array = np.expand_dims(img, axis=0) / 255.0

    # 进行预测
    prediction = model.predict(img_array)
    predicted_class_index = np.argmax(prediction[0])
    label = class_labels[predicted_class_index]  # 使用索引查找对应的人名

    # 显示结果
    cv2.putText(frame, label, (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 0, 0), 2)
    cv2.imshow('Camera', frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()